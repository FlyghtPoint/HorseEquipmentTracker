<?php
// tests/Unit/Entity/EquipmentTest.php
namespace App\Tests\Unit\Entity;

use App\Entity\Equipment;
use PHPUnit\Framework\TestCase;

class EquipmentTest extends TestCase
{
    public function testCreateEquipment(): void
    {
        $equipment = new Equipment();
        $equipment->setName('Test Equipment');
        $equipment->setDescription('Test Description');

        $this->assertEquals('Test Equipment', $equipment->getName());
        $this->assertEquals('Test Description', $equipment->getDescription());
    }
}