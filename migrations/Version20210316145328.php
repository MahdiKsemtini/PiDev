<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316145328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_loisir ADD id_fr_id INT DEFAULT NULL, ADD id_so_id INT DEFAULT NULL, DROP id_u');
        $this->addSql('ALTER TABLE event_loisir ADD CONSTRAINT FK_FDA2573266E340F5 FOREIGN KEY (id_fr_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE event_loisir ADD CONSTRAINT FK_FDA257326C90D745 FOREIGN KEY (id_so_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_FDA2573266E340F5 ON event_loisir (id_fr_id)');
        $this->addSql('CREATE INDEX IDX_FDA257326C90D745 ON event_loisir (id_so_id)');
        $this->addSql('ALTER TABLE formation ADD id_fr_id INT DEFAULT NULL, ADD id_so_id INT DEFAULT NULL, DROP id_u');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF66E340F5 FOREIGN KEY (id_fr_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF6C90D745 FOREIGN KEY (id_so_id) REFERENCES societe (id)');
        $this->addSql('CREATE INDEX IDX_404021BF66E340F5 ON formation (id_fr_id)');
        $this->addSql('CREATE INDEX IDX_404021BF6C90D745 ON formation (id_so_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_loisir DROP FOREIGN KEY FK_FDA2573266E340F5');
        $this->addSql('ALTER TABLE event_loisir DROP FOREIGN KEY FK_FDA257326C90D745');
        $this->addSql('DROP INDEX IDX_FDA2573266E340F5 ON event_loisir');
        $this->addSql('DROP INDEX IDX_FDA257326C90D745 ON event_loisir');
        $this->addSql('ALTER TABLE event_loisir ADD id_u INT NOT NULL, DROP id_fr_id, DROP id_so_id');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF66E340F5');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF6C90D745');
        $this->addSql('DROP INDEX IDX_404021BF66E340F5 ON formation');
        $this->addSql('DROP INDEX IDX_404021BF6C90D745 ON formation');
        $this->addSql('ALTER TABLE formation ADD id_u INT NOT NULL, DROP id_fr_id, DROP id_so_id');
    }
}
