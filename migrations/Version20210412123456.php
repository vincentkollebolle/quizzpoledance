<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412123456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E5C011B5');
        $this->addSql('DROP INDEX IDX_B6F7494E5C011B5 ON question');
        $this->addSql('ALTER TABLE question ADD mediaurl VARCHAR(255) DEFAULT NULL, DROP figure_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD figure_id INT NOT NULL, DROP mediaurl');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E5C011B5 ON question (figure_id)');
    }
}
