<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328155223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires ADD id_util_id INT DEFAULT NULL, ADD societe_id INT DEFAULT NULL, DROP id_util');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C411C087F0 FOREIGN KEY (id_util_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C411C087F0 ON commentaires (id_util_id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4FCF77503 ON commentaires (societe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C411C087F0');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FCF77503');
        $this->addSql('DROP INDEX IDX_D9BEC0C411C087F0 ON commentaires');
        $this->addSql('DROP INDEX IDX_D9BEC0C4FCF77503 ON commentaires');
        $this->addSql('ALTER TABLE commentaires ADD id_util INT NOT NULL, DROP id_util_id, DROP societe_id');
    }
}
