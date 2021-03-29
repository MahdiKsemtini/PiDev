<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323003147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_emploi ADD freelancer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_emploi ADD CONSTRAINT FK_36E4F6158545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('CREATE INDEX IDX_36E4F6158545BDF5 ON demande_emploi (freelancer_id)');
        $this->addSql('ALTER TABLE demande_stage ADD freelancer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_stage ADD CONSTRAINT FK_34A210408545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('CREATE INDEX IDX_34A210408545BDF5 ON demande_stage (freelancer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_emploi DROP FOREIGN KEY FK_36E4F6158545BDF5');
        $this->addSql('DROP INDEX IDX_36E4F6158545BDF5 ON demande_emploi');
        $this->addSql('ALTER TABLE demande_emploi DROP freelancer_id');
        $this->addSql('ALTER TABLE demande_stage DROP FOREIGN KEY FK_34A210408545BDF5');
        $this->addSql('DROP INDEX IDX_34A210408545BDF5 ON demande_stage');
        $this->addSql('ALTER TABLE demande_stage DROP freelancer_id');
    }
}
