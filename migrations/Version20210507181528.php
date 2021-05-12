<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507181528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT NOT NULL, brand VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE car (id INT NOT NULL, fuel_id INT NOT NULL, brand_id INT NOT NULL, modele_id INT NOT NULL, color_id INT NOT NULL, gearbox_id INT NOT NULL, office_id INT NOT NULL, horsepower SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, daily_price SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_773DE69D97C79677 ON car (fuel_id)');
        $this->addSql('CREATE INDEX IDX_773DE69D44F5D008 ON car (brand_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DAC14B70A ON car (modele_id)');
        $this->addSql('CREATE INDEX IDX_773DE69D7ADA1FB5 ON car (color_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DC826082F ON car (gearbox_id)');
        $this->addSql('CREATE INDEX IDX_773DE69DFFA0C224 ON car (office_id)');
        $this->addSql('CREATE TABLE car_type (car_id INT NOT NULL, type_id INT NOT NULL, PRIMARY KEY(car_id, type_id))');
        $this->addSql('CREATE INDEX IDX_47B44385C3C6F69F ON car_type (car_id)');
        $this->addSql('CREATE INDEX IDX_47B44385C54C8C93 ON car_type (type_id)');
        $this->addSql('CREATE TABLE car_option (car_id INT NOT NULL, option_id INT NOT NULL, PRIMARY KEY(car_id, option_id))');
        $this->addSql('CREATE INDEX IDX_42EEEC42C3C6F69F ON car_option (car_id)');
        $this->addSql('CREATE INDEX IDX_42EEEC42A7C41D6F ON car_option (option_id)');
        $this->addSql('CREATE TABLE car_car_image (car_id INT NOT NULL, car_image_id INT NOT NULL, PRIMARY KEY(car_id, car_image_id))');
        $this->addSql('CREATE INDEX IDX_1D7473A4C3C6F69F ON car_car_image (car_id)');
        $this->addSql('CREATE INDEX IDX_1D7473A4B2931069 ON car_car_image (car_image_id)');
        $this->addSql('CREATE TABLE car_image (id INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE city (id INT NOT NULL, name VARCHAR(100) NOT NULL, code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE color (id INT NOT NULL, color VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, name VARCHAR(80) NOT NULL, code INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fuel (id INT NOT NULL, fuel VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE gearbox (id INT NOT NULL, gearbox VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE modele (id INT NOT NULL, modele VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE offices (id INT NOT NULL, city_id INT NOT NULL, street VARCHAR(200) NOT NULL, tel_number VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F574FF4C8BAC62AF ON offices (city_id)');
        $this->addSql('CREATE TABLE option (id INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rent (id INT NOT NULL, pickup_office_id INT NOT NULL, return_office_id INT NOT NULL, user_id INT NOT NULL, price SMALLINT NOT NULL, pickup_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, return_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2784DCCD6ACA97 ON rent (pickup_office_id)');
        $this->addSql('CREATE INDEX IDX_2784DCCAB97C94 ON rent (return_office_id)');
        $this->addSql('CREATE INDEX IDX_2784DCCA76ED395 ON rent (user_id)');
        $this->addSql('CREATE TABLE type (id INT NOT NULL, type VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, city_id INT NOT NULL, country_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(80) NOT NULL, first_name VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6498BAC62AF ON "user" (city_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F92F3E70 ON "user" (country_id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D97C79677 FOREIGN KEY (fuel_id) REFERENCES fuel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC826082F FOREIGN KEY (gearbox_id) REFERENCES gearbox (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DFFA0C224 FOREIGN KEY (office_id) REFERENCES offices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_type ADD CONSTRAINT FK_47B44385C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_type ADD CONSTRAINT FK_47B44385C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_option ADD CONSTRAINT FK_42EEEC42C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_option ADD CONSTRAINT FK_42EEEC42A7C41D6F FOREIGN KEY (option_id) REFERENCES option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_car_image ADD CONSTRAINT FK_1D7473A4C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_car_image ADD CONSTRAINT FK_1D7473A4B2931069 FOREIGN KEY (car_image_id) REFERENCES car_image (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE offices ADD CONSTRAINT FK_F574FF4C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCD6ACA97 FOREIGN KEY (pickup_office_id) REFERENCES offices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCAB97C94 FOREIGN KEY (return_office_id) REFERENCES offices (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D44F5D008');
        $this->addSql('ALTER TABLE car_type DROP CONSTRAINT FK_47B44385C3C6F69F');
        $this->addSql('ALTER TABLE car_option DROP CONSTRAINT FK_42EEEC42C3C6F69F');
        $this->addSql('ALTER TABLE car_car_image DROP CONSTRAINT FK_1D7473A4C3C6F69F');
        $this->addSql('ALTER TABLE car_car_image DROP CONSTRAINT FK_1D7473A4B2931069');
        $this->addSql('ALTER TABLE offices DROP CONSTRAINT FK_F574FF4C8BAC62AF');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D7ADA1FB5');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F92F3E70');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D97C79677');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DC826082F');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DAC14B70A');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DFFA0C224');
        $this->addSql('ALTER TABLE rent DROP CONSTRAINT FK_2784DCCD6ACA97');
        $this->addSql('ALTER TABLE rent DROP CONSTRAINT FK_2784DCCAB97C94');
        $this->addSql('ALTER TABLE car_option DROP CONSTRAINT FK_42EEEC42A7C41D6F');
        $this->addSql('ALTER TABLE car_type DROP CONSTRAINT FK_47B44385C54C8C93');
        $this->addSql('ALTER TABLE rent DROP CONSTRAINT FK_2784DCCA76ED395');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_type');
        $this->addSql('DROP TABLE car_option');
        $this->addSql('DROP TABLE car_car_image');
        $this->addSql('DROP TABLE car_image');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE fuel');
        $this->addSql('DROP TABLE gearbox');
        $this->addSql('DROP TABLE modele');
        $this->addSql('DROP TABLE offices');
        $this->addSql('DROP TABLE option');
        $this->addSql('DROP TABLE rent');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE "user"');
    }
}
