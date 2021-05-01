<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501182713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD office_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD daily_price SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DFFA0C224 FOREIGN KEY (office_id) REFERENCES offices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_773DE69DFFA0C224 ON car (office_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DFFA0C224');
        $this->addSql('DROP INDEX IDX_773DE69DFFA0C224');
        $this->addSql('ALTER TABLE car DROP office_id');
        $this->addSql('ALTER TABLE car DROP daily_price');
    }
}
