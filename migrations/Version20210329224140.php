<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329224140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin_event (id INT AUTO_INCREMENT NOT NULL, id_a_e INT NOT NULL, id_event_loisir INT DEFAULT NULL, id_formation INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_emploi DROP id_demande_emploi, DROP id_demande_stage');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin_event');
        $this->addSql('ALTER TABLE admin_emploi ADD id_demande_emploi INT DEFAULT NULL, ADD id_demande_stage INT DEFAULT NULL');
    }
}
