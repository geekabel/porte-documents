<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725110438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8698A76F675F31B ON document (author_id)');
        $this->addSql('ALTER TABLE porte_document DROP FOREIGN KEY FK_B3B3EF7FC6E59929');
        $this->addSql('DROP INDEX IDX_B3B3EF7FC6E59929 ON porte_document');
        $this->addSql('ALTER TABLE porte_document ADD description VARCHAR(255) DEFAULT NULL, DROP autheur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76F675F31B');
        $this->addSql('DROP INDEX IDX_D8698A76F675F31B ON document');
        $this->addSql('ALTER TABLE document DROP author_id');
        $this->addSql('ALTER TABLE porte_document ADD autheur_id INT DEFAULT NULL, DROP description');
        $this->addSql('ALTER TABLE porte_document ADD CONSTRAINT FK_B3B3EF7FC6E59929 FOREIGN KEY (autheur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B3B3EF7FC6E59929 ON porte_document (autheur_id)');
    }
}
