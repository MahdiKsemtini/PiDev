<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328125203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reviews_textual (id INT AUTO_INCREMENT NOT NULL, societe_id INT DEFAULT NULL, freelancer_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, id_taker INT NOT NULL, INDEX IDX_A99AED85FCF77503 (societe_id), INDEX IDX_A99AED858545BDF5 (freelancer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reviews_textual ADD CONSTRAINT FK_A99AED85FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE reviews_textual ADD CONSTRAINT FK_A99AED858545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reviews_textual');
    }
}
