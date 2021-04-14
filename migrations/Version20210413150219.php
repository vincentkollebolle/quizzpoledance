<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413150219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playeranswer ADD quizz_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playeranswer ADD CONSTRAINT FK_EF97DDFFBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('CREATE INDEX IDX_EF97DDFFBA934BCD ON playeranswer (quizz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playeranswer DROP FOREIGN KEY FK_EF97DDFFBA934BCD');
        $this->addSql('DROP INDEX IDX_EF97DDFFBA934BCD ON playeranswer');
        $this->addSql('ALTER TABLE playeranswer DROP quizz_id');
    }
}
