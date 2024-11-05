<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\{Equipment, Category, Location, Condition, User, Reservation, Movement};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'une instance de Faker
        $faker = Factory::create('fr_FR');

        // Fonction de création d'utilisateur
        $createUser = function($email, $password, $roles) use ($manager) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->hasher->hashPassword($user, $password));
            $user->setRoles($roles);
            $manager->persist($user);
        };

        // Création de 9 utilisateurs 
        for ($i = 1; $i < 10; $i++) {
            $createUser($faker->email, 'password', ['ROLE_USER']);
        }

        // Création d'un utilisateur admin
        $createUser('admin@test.fr', 'admin', ['ROLE_ADMIN']);

        $manager->flush();

        // Création des catégories
        $categories = ['brides', 'selles', 'tapis', 'bottes', 'gants', 'bombe', 'cravache', 'étriers'];
        
        foreach ($categories as $c) {
            $category = new Category();
            $category->setName($c);
            $manager->persist($category);
        }

        // Création de l'état des équipements
        $conditions = ['neuf', 'bon état', 'usé', 'en réparation', 'hors service'];

        foreach ($conditions as $c) {
            $condition = new Condition();
            $condition->setName($c);
            $manager->persist($condition);
        }

        $manager->flush();

        // Récupération des catégories et états
        $allCategories = $manager->getRepository(Category::class)->findAll();
        $allConditions = $manager->getRepository(Condition::class)->findAll();

        // Génération de toutes les combinaisons d'emplacements
        $aisles = range('A', 'J');
        $shelves = range(1, 10);
        $combinations = [];

        foreach ($aisles as $a) {
            foreach ($shelves as $s) {
                $combinations[] = ['aisle' => $a, 'shelf' => $s];
            }
        }

        // Mélange des combinaisons
        shuffle($combinations);

        // Création des emplacements avec des combinaisons uniques
        foreach ($combinations as $c) {
            $location = new Location();
            $location->setAisle($c['aisle']);
            $location->setShelf($c['shelf']);
            $manager->persist($location);
        }

        $manager->flush();

        // Récupération des emplacements
        $allLocations = $manager->getRepository(Location::class)->findAll();

        // Création de 10 équipements
        for ($i = 1; $i <= 10; $i++) {
            $equipment = new Equipment();
            $equipment->setName($faker->word);
            $equipment->setDescription($faker->sentence);
            $equipment->setCategory($faker->randomElement($allCategories));
            $equipment->setLocation($faker->randomElement($allLocations));
            $equipment->setECondition($faker->randomElement($allConditions));
            $manager->persist($equipment);
        }

        $manager->flush();

        // Création de 5 réservations

        // Récupération de tous les utilisateurs et équipements
        $allUsers = $manager->getRepository(User::class)->findAll();
        $allEquipments = $manager->getRepository(Equipment::class)->findAll();

        $reservations = [];

        for ($i = 1; $i <= 5; $i++) {
            $reservation = new Reservation();
            $reservation->setUser($faker->randomElement($allUsers));

            // Vérification de la disponibilité de l'équipement
            do {
                $equipment = $faker->randomElement($allEquipments);
                $startDate = $faker->dateTimeBetween('now', '+1 month');
                $endDate = $faker->dateTimeBetween($startDate, '+1 month');
                $overlap = false;

                foreach ($reservations as $existingReservation) {
                    if ($existingReservation->getEquipment() === $equipment && !($endDate < $existingReservation->getStartDate() || $startDate > $existingReservation->getEndDate())) {
                        $overlap = true;
                        break;
                    }
                }
            } while ($overlap);

            $reservation->setEquipment($equipment);
            $reservation->setStartDate($startDate);
            $reservation->setEndDate($endDate);
            $reservation->setStatus($faker->randomElement(['pending', 'accepted', 'canceled']));
            $reservation->setType($faker->randomElement(['loan', 'maintenance', 'repair']));
            $manager->persist($reservation);
            $reservations[] = $reservation;
        }

        $manager->flush();

        // Récupération de toutes les réservations existantes
        $allReservations = $manager->getRepository(Reservation::class)->findAll();

        foreach ($allReservations as $reservation) {
            // Création du mouvement de type 'in'
            $movementIn = new Movement();
            $movementIn->setReservation($reservation);
            $movementIn->setType('in');
            $movementIn->setDate($reservation->getStartDate());
            $manager->persist($movementIn);

            // Création du mouvement de type 'out'
            $movementOut = new Movement();
            $movementOut->setReservation($reservation);
            $movementOut->setType('out');
            $movementOut->setDate($reservation->getEndDate());
            $manager->persist($movementOut);
        }

        $manager->flush();
    }
}
