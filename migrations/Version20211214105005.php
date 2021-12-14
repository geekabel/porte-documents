<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214105005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD size INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD delete_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD is_delete BOOLEAN NOT NULL');
        $this->addSql('COMMENT ON COLUMN document.delete_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE porte_document ADD delete_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE porte_document ADD is_delete BOOLEAN NOT NULL');
        $this->addSql('COMMENT ON COLUMN porte_document.delete_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE porte_document DROP delete_at');
        $this->addSql('ALTER TABLE porte_document DROP is_delete');
        $this->addSql('ALTER TABLE document DROP size');
        $this->addSql('ALTER TABLE document DROP delete_at');
        $this->addSql('ALTER TABLE document DROP is_delete');
    }
}
