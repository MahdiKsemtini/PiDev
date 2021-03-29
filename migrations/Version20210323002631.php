<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323002631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_stage ADD offre_stage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_stage ADD CONSTRAINT FK_34A21040195A2A28 FOREIGN KEY (offre_stage_id) REFERENCES offre_stage (id)');
        $this->addSql('CREATE INDEX IDX_34A21040195A2A28 ON demande_stage (offre_stage_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_stage DROP FOREIGN KEY FK_34A21040195A2A28');
        $this->addSql('DROP INDEX IDX_34A21040195A2A28 ON demande_stage');
        $this->addSql('ALTER TABLE demande_stage DROP offre_stage_id');
    }
}
