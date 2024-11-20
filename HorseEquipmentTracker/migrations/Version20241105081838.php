<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105081838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment ADD location_id INT NOT NULL, ADD e_condition_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D58364D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE equipment ADD CONSTRAINT FK_D338D58349D2F1E FOREIGN KEY (e_condition_id) REFERENCES `condition` (id)');
        $this->addSql('CREATE INDEX IDX_D338D58364D218E ON equipment (location_id)');
        $this->addSql('CREATE INDEX IDX_D338D58349D2F1E ON equipment (e_condition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D58364D218E');
        $this->addSql('ALTER TABLE equipment DROP FOREIGN KEY FK_D338D58349D2F1E');
        $this->addSql('DROP INDEX IDX_D338D58364D218E ON equipment');
        $this->addSql('DROP INDEX IDX_D338D58349D2F1E ON equipment');
        $this->addSql('ALTER TABLE equipment DROP location_id, DROP e_condition_id');
    }
}
