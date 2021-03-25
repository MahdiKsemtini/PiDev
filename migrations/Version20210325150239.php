<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325150239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE freelancer ADD compte_linkedin VARCHAR(255) NOT NULL, ADD compte_twitter VARCHAR(255) NOT NULL, ADD views_nb INT NOT NULL, ADD etat INT NOT NULL, CHANGE comptes_reseaux_sociaux compte_facebook VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE societe ADD views_nb INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE freelancer ADD comptes_reseaux_sociaux VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP compte_facebook, DROP compte_linkedin, DROP compte_twitter, DROP views_nb, DROP etat');
        $this->addSql('ALTER TABLE societe DROP views_nb');
    }
}
