<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905082711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP INDEX UNIQ_F2A10625A4AD38A8, ADD INDEX IDX_F2A10625A4AD38A8 (id_organizer_id)');
        $this->addSql('ALTER TABLE outing ADD CONSTRAINT FK_F2A10625A4AD38A8 FOREIGN KEY (id_organizer_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outing DROP INDEX IDX_F2A10625A4AD38A8, ADD UNIQUE INDEX UNIQ_F2A10625A4AD38A8 (id_organizer_id)');
        $this->addSql('ALTER TABLE outing DROP FOREIGN KEY FK_F2A10625A4AD38A8');
    }
}
