<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412123205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E272149B7');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E3594E659');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E8D28813C');
        $this->addSql('DROP INDEX IDX_B6F7494E272149B7 ON question');
        $this->addSql('DROP INDEX IDX_B6F7494E8D28813C ON question');
        $this->addSql('DROP INDEX IDX_B6F7494E3594E659 ON question');
        $this->addSql('ALTER TABLE question ADD choice1 VARCHAR(255) NOT NULL, ADD choice2 VARCHAR(255) NOT NULL, ADD choice3 VARCHAR(255) NOT NULL, ADD choice4 VARCHAR(255) NOT NULL, DROP choice1_id, DROP choice2_id, DROP choice3_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD choice1_id INT NOT NULL, ADD choice2_id INT NOT NULL, ADD choice3_id INT NOT NULL, DROP choice1, DROP choice2, DROP choice3, DROP choice4');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E272149B7 FOREIGN KEY (choice1_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3594E659 FOREIGN KEY (choice2_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E8D28813C FOREIGN KEY (choice3_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E272149B7 ON question (choice1_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E8D28813C ON question (choice3_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E3594E659 ON question (choice2_id)');
    }
}
