<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015062313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD dummy_field VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE status status ENUM("pending", "confirmed", "canceled") NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE type type ENUM("type1", "type2", "type3") NOT NULL');
        $this->addSql('ALTER TABLE movement CHANGE type type ENUM("in", "out") NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP dummy_field');
        $this->addSql('ALTER TABLE reservation CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE movement CHANGE type type VARCHAR(255) NOT NULL');
    }
}
