<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903080605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
        $this->addSql('CREATE TABLE outing_user (outing_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2CCED92AF4C7531 (outing_id), INDEX IDX_2CCED92A76ED395 (user_id), PRIMARY KEY(outing_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_campus_id INT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_8D93D6492F68E9C6 (id_campus_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE outing_user ADD CONSTRAINT FK_2CCED92AF4C7531 FOREIGN KEY (outing_id) REFERENCES outing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outing_user ADD CONSTRAINT FK_2CCED92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492F68E9C6 FOREIGN KEY (id_campus_id) REFERENCES campus (id)');
        $this->addSql('ALTER TABLE outing_users DROP FOREIGN KEY FK_50DF18E67B3B43D');
        $this->addSql('ALTER TABLE outing_users DROP FOREIGN KEY FK_50DF18EAF4C7531');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E92F68E9C6');
        $this->addSql('DROP TABLE outing_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625A4AD38A8 FOREIGN KEY (id_organizer_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
        $this->addSql('CREATE TABLE outing_users (outing_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_50DF18EAF4C7531 (outing_id), INDEX IDX_50DF18E67B3B43D (users_id), PRIMARY KEY(outing_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, id_campus_id INT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, profile_picture VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1483A5E92F68E9C6 (id_campus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE outing_users ADD CONSTRAINT FK_50DF18E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE outing_users ADD CONSTRAINT FK_50DF18EAF4C7531 FOREIGN KEY (outing_id) REFERENCES outing (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E92F68E9C6 FOREIGN KEY (id_campus_id) REFERENCES campus (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE outing_user DROP FOREIGN KEY FK_2CCED92AF4C7531');
        $this->addSql('ALTER TABLE outing_user DROP FOREIGN KEY FK_2CCED92A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492F68E9C6');
        $this->addSql('DROP TABLE outing_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625A4AD38A8 FOREIGN KEY (id_organizer_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
