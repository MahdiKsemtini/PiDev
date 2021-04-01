<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210326173639 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, approuve INT DEFAULT NULL, nonapprouve INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_emploi (id INT AUTO_INCREMENT NOT NULL, id_a_e INT NOT NULL, id_offre_emploi INT DEFAULT NULL, id_offre_stage INT DEFAULT NULL, id_demande_emploi INT DEFAULT NULL, id_demande_stage INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_reclamtion (id INT AUTO_INCREMENT NOT NULL, id_a_r INT NOT NULL, id_reclamation INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, id_pub_id INT NOT NULL, id_util INT NOT NULL, description VARCHAR(1000) NOT NULL, date_com DATETIME NOT NULL, INDEX IDX_D9BEC0C4A5CA559A (id_pub_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, date_expiration DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_132AD0D1FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_stage (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, nom_projet VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, type_stage VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, etat TINYINT(1) NOT NULL, INDEX IDX_955674F2FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publications (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, description VARCHAR(1000) NOT NULL, image VARCHAR(1000) DEFAULT NULL, date_publication DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, texte_reclamation VARCHAR(255) NOT NULL, date_reclamation VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(255) NOT NULL, nom_utilisateur VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE super_admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4A5CA559A FOREIGN KEY (id_pub_id) REFERENCES publications (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE offre_stage ADD CONSTRAINT FK_955674F2FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4A5CA559A');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE admin_emploi');
        $this->addSql('DROP TABLE admin_reclamtion');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE offre_stage');
        $this->addSql('DROP TABLE publications');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE super_admin');
    }
}
