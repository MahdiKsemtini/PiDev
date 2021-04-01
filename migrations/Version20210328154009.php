<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328154009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publications ADD societe_id INT DEFAULT NULL, CHANGE id_utilisateur freelancer_id INT NOT NULL');
        $this->addSql('ALTER TABLE publications ADD CONSTRAINT FK_32783AF48545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE publications ADD CONSTRAINT FK_32783AF4FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_32783AF48545BDF5 ON publications (freelancer_id)');
        $this->addSql('CREATE INDEX IDX_32783AF4FCF77503 ON publications (societe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publications DROP FOREIGN KEY FK_32783AF48545BDF5');
        $this->addSql('ALTER TABLE publications DROP FOREIGN KEY FK_32783AF4FCF77503');
        $this->addSql('DROP INDEX IDX_32783AF48545BDF5 ON publications');
        $this->addSql('DROP INDEX IDX_32783AF4FCF77503 ON publications');
        $this->addSql('ALTER TABLE publications DROP societe_id, CHANGE freelancer_id id_utilisateur INT NOT NULL');
    }
}
