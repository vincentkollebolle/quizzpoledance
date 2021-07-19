<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719083424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_58DF0651E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playeranswer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, quizz_id INT DEFAULT NULL, pickedanswer_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_EF97DDFF1E27F6BF (question_id), INDEX IDX_EF97DDFFBA934BCD (quizz_id), INDEX IDX_EF97DDFFAC48156D (pickedanswer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, goodanswer_id INT NOT NULL, mediaurl VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E7128F3A0 (goodanswer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_answer (question_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_DD80652D1E27F6BF (question_id), INDEX IDX_DD80652DAA334807 (answer_id), PRIMARY KEY(question_id, answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id INT AUTO_INCREMENT NOT NULL, playername VARCHAR(255) NOT NULL, score INT NOT NULL, date DATETIME DEFAULT NULL, combo INT UNSIGNED DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playeranswer ADD CONSTRAINT FK_EF97DDFF1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE playeranswer ADD CONSTRAINT FK_EF97DDFFBA934BCD FOREIGN KEY (quizz_id) REFERENCES quizz (id)');
        $this->addSql('ALTER TABLE playeranswer ADD CONSTRAINT FK_EF97DDFFAC48156D FOREIGN KEY (pickedanswer_id) REFERENCES answer (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E7128F3A0 FOREIGN KEY (goodanswer_id) REFERENCES answer (id)');
        $this->addSql('ALTER TABLE question_answer ADD CONSTRAINT FK_DD80652D1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_answer ADD CONSTRAINT FK_DD80652DAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playeranswer DROP FOREIGN KEY FK_EF97DDFFAC48156D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E7128F3A0');
        $this->addSql('ALTER TABLE question_answer DROP FOREIGN KEY FK_DD80652DAA334807');
        $this->addSql('ALTER TABLE playeranswer DROP FOREIGN KEY FK_EF97DDFF1E27F6BF');
        $this->addSql('ALTER TABLE question_answer DROP FOREIGN KEY FK_DD80652D1E27F6BF');
        $this->addSql('ALTER TABLE playeranswer DROP FOREIGN KEY FK_EF97DDFFBA934BCD');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE playeranswer');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_answer');
        $this->addSql('DROP TABLE quizz');
    }
}
