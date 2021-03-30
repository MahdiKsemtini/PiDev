<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329192415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_loisir (id INT AUTO_INCREMENT NOT NULL, id_fr_id INT DEFAULT NULL, id_so_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, lieu VARCHAR(255) NOT NULL, labelle VARCHAR(255) NOT NULL, nb_participant INT NOT NULL, domaine VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, lng DOUBLE PRECISION DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, INDEX IDX_FDA2573266E340F5 (id_fr_id), INDEX IDX_FDA257326C90D745 (id_so_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, id_fr_id INT DEFAULT NULL, id_so_id INT DEFAULT NULL, description VARCHAR(100) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, lieu VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, labelle VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, lng DOUBLE PRECISION DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, INDEX IDX_404021BF66E340F5 (id_fr_id), INDEX IDX_404021BF6C90D745 (id_so_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id_p INT AUTO_INCREMENT NOT NULL, id_f_id INT DEFAULT NULL, id_s_id INT DEFAULT NULL, id_fo_id INT DEFAULT NULL, id_e_id INT DEFAULT NULL, type_u VARCHAR(100) NOT NULL, type_e VARCHAR(100) NOT NULL, INDEX IDX_D79F6B112D2977E3 (id_f_id), INDEX IDX_D79F6B114AEED04E (id_s_id), INDEX IDX_D79F6B11C490CFB7 (id_fo_id), INDEX IDX_D79F6B113F9CD80D (id_e_id), PRIMARY KEY(id_p)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_loisir ADD CONSTRAINT FK_FDA2573266E340F5 FOREIGN KEY (id_fr_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE event_loisir ADD CONSTRAINT FK_FDA257326C90D745 FOREIGN KEY (id_so_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF66E340F5 FOREIGN KEY (id_fr_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF6C90D745 FOREIGN KEY (id_so_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B112D2977E3 FOREIGN KEY (id_f_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B114AEED04E FOREIGN KEY (id_s_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11C490CFB7 FOREIGN KEY (id_fo_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B113F9CD80D FOREIGN KEY (id_e_id) REFERENCES event_loisir (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B113F9CD80D');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11C490CFB7');
        $this->addSql('DROP TABLE event_loisir');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE participant');
    }
}
