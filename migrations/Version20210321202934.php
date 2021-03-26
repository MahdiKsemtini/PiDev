<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210321202934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE freelancer (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, photo_de_profile VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, langues VARCHAR(255) NOT NULL, comptes_reseaux_sociaux VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE freelancer_societe (freelancer_id INT NOT NULL, societe_id INT NOT NULL, INDEX IDX_90093BEB8545BDF5 (freelancer_id), INDEX IDX_90093BEBFCF77503 (societe_id), PRIMARY KEY(freelancer_id, societe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, INDEX IDX_132AD0D1FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_stage (id INT AUTO_INCREMENT NOT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, type_stage VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_pass VARCHAR(255) NOT NULL, photo_de_profile VARCHAR(255) NOT NULL, status_juridique VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE freelancer_societe ADD CONSTRAINT FK_90093BEB8545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freelancer_societe ADD CONSTRAINT FK_90093BEBFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE freelancer_societe DROP FOREIGN KEY FK_90093BEB8545BDF5');
        $this->addSql('ALTER TABLE freelancer_societe DROP FOREIGN KEY FK_90093BEBFCF77503');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1FCF77503');
        $this->addSql('DROP TABLE freelancer');
        $this->addSql('DROP TABLE freelancer_societe');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE offre_stage');
        $this->addSql('DROP TABLE societe');
    }
}
