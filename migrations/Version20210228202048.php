<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210228202048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE formation DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE formation DROP id, CHANGE id_f id_f INT AUTO_INCREMENT NOT NULL, CHANGE description description VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE formation ADD PRIMARY KEY (id_f)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD id INT AUTO_INCREMENT NOT NULL, CHANGE id_f id_f VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
