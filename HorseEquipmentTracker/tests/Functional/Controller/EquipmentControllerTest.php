<?php
// tests/Functional/Controller/EquipmentControllerTest.php
namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EquipmentControllerTest extends WebTestCase
{
    public function testEquipmentList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/equipment');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Équipements');
    }

    public function testEquipmentListRequiresAuth(): void
    {
        $client = static::createClient();
        $client->request('GET', '/equipment/new');

        $this->assertResponseRedirects('/login');
    }
}