<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310215417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_emploi (id INT AUTO_INCREMENT NOT NULL, id_a_e INT NOT NULL, id_offre_emploi INT DEFAULT NULL, id_offre_stage INT DEFAULT NULL, id_demande_emploi INT DEFAULT NULL, id_demande_stage INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_reclamtion (id INT AUTO_INCREMENT NOT NULL, id_a_r INT NOT NULL, id_reclamtion INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, texte_reclamation VARCHAR(255) NOT NULL, date_reclamation VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(255) NOT NULL, nom_utilisateur VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin_emploi');
        $this->addSql('DROP TABLE admin_reclamtion');
        $this->addSql('DROP TABLE reclamation');
    }
}
