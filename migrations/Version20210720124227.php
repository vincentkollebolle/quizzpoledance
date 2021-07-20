<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210720124227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator CHANGE roles roles JSON NOT NULL');
        // $this->addSql('ALTER TABLE administrator RENAME INDEX uniq_98197a65e7927c74 TO UNIQ_58DF0651E7927C74');
        $this->addSql('ALTER TABLE playeranswer CHANGE question_id question_id INT DEFAULT NULL, CHANGE quizz_id quizz_id INT DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE mediaurl mediaurl VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz CHANGE date date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE administrator RENAME INDEX uniq_58df0651e7927c74 TO UNIQ_98197A65E7927C74');
        $this->addSql('ALTER TABLE playeranswer CHANGE question_id question_id INT DEFAULT NULL, CHANGE quizz_id quizz_id INT DEFAULT NULL, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE question CHANGE mediaurl mediaurl VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE quizz CHANGE date date DATETIME DEFAULT \'NULL\'');
    }
}
