<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003110815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE report_article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE report_comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE report_article (id INT NOT NULL, user_id_id INT NOT NULL, article_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62A373669D86650F ON report_article (user_id_id)');
        $this->addSql('CREATE INDEX IDX_62A373668F3EC46 ON report_article (article_id_id)');
        $this->addSql('CREATE TABLE report_comment (id INT NOT NULL, user_id_id INT NOT NULL, comment_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F4ED2F6C9D86650F ON report_comment (user_id_id)');
        $this->addSql('CREATE INDEX IDX_F4ED2F6CD6DE06A6 ON report_comment (comment_id_id)');
        $this->addSql('ALTER TABLE report_article ADD CONSTRAINT FK_62A373669D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report_article ADD CONSTRAINT FK_62A373668F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report_comment ADD CONSTRAINT FK_F4ED2F6C9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE report_comment ADD CONSTRAINT FK_F4ED2F6CD6DE06A6 FOREIGN KEY (comment_id_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE report_article_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE report_comment_id_seq CASCADE');
        $this->addSql('ALTER TABLE report_article DROP CONSTRAINT FK_62A373669D86650F');
        $this->addSql('ALTER TABLE report_article DROP CONSTRAINT FK_62A373668F3EC46');
        $this->addSql('ALTER TABLE report_comment DROP CONSTRAINT FK_F4ED2F6C9D86650F');
        $this->addSql('ALTER TABLE report_comment DROP CONSTRAINT FK_F4ED2F6CD6DE06A6');
        $this->addSql('DROP TABLE report_article');
        $this->addSql('DROP TABLE report_comment');
    }
}
