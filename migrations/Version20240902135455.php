<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902135455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campus (id INT AUTO_INCREMENT NOT NULL, campus_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, place_name VARCHAR(255) NOT NULL, city_name VARCHAR(255) NOT NULL, street_name VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outing (id INT AUTO_INCREMENT NOT NULL, id_campus_id INT DEFAULT NULL, id_city_id INT DEFAULT NULL, id_organizer_id INT DEFAULT NULL, outing_name VARCHAR(255) NOT NULL, outing_date DATETIME NOT NULL, registration_deadline DATETIME NOT NULL, slots INT NOT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_F2A106252F68E9C6 (id_campus_id), INDEX IDX_F2A106255531CBDF (id_city_id), UNIQUE INDEX UNIQ_F2A10625A4AD38A8 (id_organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outing_users (outing_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_50DF18EAF4C7531 (outing_id), INDEX IDX_50DF18E67B3B43D (users_id), PRIMARY KEY(outing_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, id_campus_id INT NOT NULL, username VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_1483A5E92F68E9C6 (id_campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A106252F68E9C6 FOREIGN KEY (id_campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A106255531CBDF FOREIGN KEY (id_city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625A4AD38A8 FOREIGN KEY (id_organizer_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE outing_users ADD CONSTRAINT FK_50DF18EAF4C7531 FOREIGN KEY (outing_id) REFERENCES outing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outing_users ADD CONSTRAINT FK_50DF18E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92F68E9C6 FOREIGN KEY (id_campus_id) REFERENCES campus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A106252F68E9C6');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A106255531CBDF');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
        $this->addSql('ALTER TABLE outing_users DROP FOREIGN KEY FK_50DF18EAF4C7531');
        $this->addSql('ALTER TABLE outing_users DROP FOREIGN KEY FK_50DF18E67B3B43D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92F68E9C6');
        $this->addSql('DROP TABLE campus');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE outing');
        $this->addSql('DROP TABLE outing_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
