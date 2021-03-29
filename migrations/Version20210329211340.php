<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329211340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_emploi ADD date_creation DATE NOT NULL, ADD date_expiration DATE NOT NULL, CHANGE quiz_id societe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_132AD0D1FCF77503 ON offre_emploi (societe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1FCF77503');
        $this->addSql('DROP INDEX IDX_132AD0D1FCF77503 ON offre_emploi');
        $this->addSql('ALTER TABLE offre_emploi DROP date_creation, DROP date_expiration, CHANGE societe_id quiz_id INT DEFAULT NULL');
    }
}
