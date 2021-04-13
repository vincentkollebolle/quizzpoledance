<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412115754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, figure_id INT NOT NULL, choice1_id INT NOT NULL, choice2_id INT NOT NULL, choice3_id INT NOT NULL, INDEX IDX_B6F7494E5C011B5 (figure_id), INDEX IDX_B6F7494E272149B7 (choice1_id), INDEX IDX_B6F7494E3594E659 (choice2_id), INDEX IDX_B6F7494E8D28813C (choice3_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E272149B7 FOREIGN KEY (choice1_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3594E659 FOREIGN KEY (choice2_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8D28813C FOREIGN KEY (choice3_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE question');
    }
}
