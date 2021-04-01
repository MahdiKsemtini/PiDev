<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330110157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_emploi (id INT AUTO_INCREMENT NOT NULL, offre_emploi_id INT DEFAULT NULL, freelancer_id INT DEFAULT NULL, salaire DOUBLE PRECISION DEFAULT NULL, diplome VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, description VARCHAR(100) NOT NULL, date_creation DATETIME NOT NULL, domaine VARCHAR(255) DEFAULT NULL, nomsociete VARCHAR(255) NOT NULL, INDEX IDX_36E4F615B08996ED (offre_emploi_id), INDEX IDX_36E4F6158545BDF5 (freelancer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_stage (id INT AUTO_INCREMENT NOT NULL, offre_stage_id INT DEFAULT NULL, freelancer_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, duree INT NOT NULL, etude VARCHAR(100) DEFAULT NULL, description VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, domaine VARCHAR(255) NOT NULL, nom_societe VARCHAR(255) NOT NULL, INDEX IDX_34A21040195A2A28 (offre_stage_id), INDEX IDX_34A210408545BDF5 (freelancer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_reponses_condidat (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, INDEX IDX_37D95AC853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, rep_just_id INT DEFAULT NULL, quiz_id_id INT DEFAULT NULL, contenu_ques VARCHAR(255) NOT NULL, nomb_rep INT NOT NULL, UNIQUE INDEX UNIQ_B6F7494E31A4897A (rep_just_id), INDEX IDX_B6F7494E8337E7D7 (quiz_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, offre_emploi_id INT DEFAULT NULL, nom_quiz VARCHAR(255) NOT NULL, nomb_question INT NOT NULL, id_societe INT NOT NULL, INDEX IDX_A412FA92B08996ED (offre_emploi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_ques_id INT DEFAULT NULL, contenu_rep VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC7E359DA8E (id_ques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_condidat (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, reponse_id INT DEFAULT NULL, list_reponses_condidat_id INT DEFAULT NULL, INDEX IDX_6506405B1E27F6BF (question_id), INDEX IDX_6506405BCF18BB82 (reponse_id), INDEX IDX_6506405B94A112E9 (list_reponses_condidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_list (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_emploi ADD CONSTRAINT FK_36E4F615B08996ED FOREIGN KEY (offre_emploi_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE demande_emploi ADD CONSTRAINT FK_36E4F6158545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE demande_stage ADD CONSTRAINT FK_34A21040195A2A28 FOREIGN KEY (offre_stage_id) REFERENCES offre_stage (id)');
        $this->addSql('ALTER TABLE demande_stage ADD CONSTRAINT FK_34A210408545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE list_reponses_condidat ADD CONSTRAINT FK_37D95AC853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E31A4897A FOREIGN KEY (rep_just_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8337E7D7 FOREIGN KEY (quiz_id_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92B08996ED FOREIGN KEY (offre_emploi_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7E359DA8E FOREIGN KEY (id_ques_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405BCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_condidat ADD CONSTRAINT FK_6506405B94A112E9 FOREIGN KEY (list_reponses_condidat_id) REFERENCES list_reponses_condidat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405B94A112E9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7E359DA8E');
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405B1E27F6BF');
        $this->addSql('ALTER TABLE list_reponses_condidat DROP FOREIGN KEY FK_37D95AC853CD175');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E8337E7D7');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E31A4897A');
        $this->addSql('ALTER TABLE reponse_condidat DROP FOREIGN KEY FK_6506405BCF18BB82');
        $this->addSql('DROP TABLE demande_emploi');
        $this->addSql('DROP TABLE demande_stage');
        $this->addSql('DROP TABLE list_reponses_condidat');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_condidat');
        $this->addSql('DROP TABLE reponse_list');
    }
}
