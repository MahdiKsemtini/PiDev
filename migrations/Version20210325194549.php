<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325194549 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, date_expiration DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_132AD0D1FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_stage (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, type_stage VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_955674F2FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE offre_stage ADD CONSTRAINT FK_955674F2FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE offre_stage');
    }
}
