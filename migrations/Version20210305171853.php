<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305171853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, text_avis VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(255) NOT NULL, nom_utilisateur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE freelancer (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, photo_de_profile VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, competences VARCHAR(255) NOT NULL, langues VARCHAR(255) NOT NULL, comptes_reseaux_sociaux VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE freelancer_societe (freelancer_id INT NOT NULL, societe_id INT NOT NULL, INDEX IDX_90093BEB8545BDF5 (freelancer_id), INDEX IDX_90093BEBFCF77503 (societe_id), PRIMARY KEY(freelancer_id, societe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_reponses_condidat (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, INDEX IDX_37D95AC853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, rep_just_id INT DEFAULT NULL, quiz_id_id INT DEFAULT NULL, contenu_ques VARCHAR(255) NOT NULL, nomb_rep INT NOT NULL, UNIQUE INDEX UNIQ_B6F7494E31A4897A (rep_just_id), INDEX IDX_B6F7494E8337E7D7 (quiz_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, nom_quiz VARCHAR(255) NOT NULL, nomb_question INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, texte_reclamation VARCHAR(255) NOT NULL, date_reclamation VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(255) NOT NULL, nom_utilisateur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_ques_id INT DEFAULT NULL, contenu_rep VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC7E359DA8E (id_ques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_condidat (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, reponse_id INT DEFAULT NULL, list_reponses_condidat_id INT DEFAULT NULL, INDEX IDX_6506405B1E27F6BF (question_id), INDEX IDX_6506405BCF18BB82 (reponse_id), INDEX IDX_6506405B94A112E9 (list_reponses_condidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_list (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_pass VARCHAR(255) NOT NULL, photo_de_profile VARCHAR(255) NOT NULL, status_juridique VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE freelancer_societe ADD CONSTRAINT FK_90093BEB8545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE freelancer_societe ADD CONSTRAINT FK_90093BEBFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE list_reponses_condidat ADD CONSTRAINT FK_37D95AC853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E31A4897A FOREIGN KEY (rep_just_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7E359DA8E FOREIGN KEY (id_ques_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405BCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405B94A112E9 FOREIGN KEY (list_reponses_condidat_id) REFERENCES list_reponses_condidat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE freelancer_societe DROP FOREIGN KEY FK_90093BEB8545BDF5');
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405B94A112E9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7E359DA8E');
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405B1E27F6BF');
        $this->addSql('ALTER TABLE list_reponses_condidat DROP FOREIGN KEY FK_37D95AC853CD175');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E8337E7D7');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E31A4897A');
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405BCF18BB82');
        $this->addSql('ALTER TABLE freelancer_societe DROP FOREIGN KEY FK_90093BEBFCF77503');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE freelancer');
        $this->addSql('DROP TABLE freelancer_societe');
        $this->addSql('DROP TABLE list_reponses_condidat');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_condidat');
        $this->addSql('DROP TABLE reponse_list');
        $this->addSql('DROP TABLE societe');
    }
}
