<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305154627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires ADD id_pub_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4A5CA559A FOREIGN KEY (id_pub_id) REFERENCES publications (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4A5CA559A ON commentaires (id_pub_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4A5CA559A');
        $this->addSql('DROP INDEX IDX_D9BEC0C4A5CA559A ON commentaires');
        $this->addSql('ALTER TABLE commentaires DROP id_pub_id');
    }
}
