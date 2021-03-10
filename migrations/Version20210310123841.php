<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310123841 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_emploi ADD offre_emploi_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_emploi ADD CONSTRAINT FK_36E4F615B08996ED FOREIGN KEY (offre_emploi_id) REFERENCES offre_emploi (id)');
        $this->addSql('CREATE INDEX IDX_36E4F615B08996ED ON demande_emploi (offre_emploi_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_emploi DROP FOREIGN KEY FK_36E4F615B08996ED');
        $this->addSql('DROP INDEX IDX_36E4F615B08996ED ON demande_emploi');
        $this->addSql('ALTER TABLE demande_emploi DROP offre_emploi_id');
    }
}
