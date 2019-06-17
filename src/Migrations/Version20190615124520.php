<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190615124520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subforum (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, subforumid_id INT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_31204C8358E0A285 (userid_id), INDEX IDX_31204C83BE11A938 (subforumid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8358E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83BE11A938 FOREIGN KEY (subforumid_id) REFERENCES subforum (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83BE11A938');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8358E0A285');
        $this->addSql('DROP TABLE subforum');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE user');
    }
}
