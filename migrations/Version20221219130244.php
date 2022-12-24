<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219130244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE works_likes (id INT AUTO_INCREMENT NOT NULL, likes_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_6016372B2F23775F (likes_id), INDEX IDX_6016372BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE works_likes ADD CONSTRAINT FK_6016372B2F23775F FOREIGN KEY (likes_id) REFERENCES works (id)');
        $this->addSql('ALTER TABLE works_likes ADD CONSTRAINT FK_6016372BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE works_likes DROP FOREIGN KEY FK_6016372B2F23775F');
        $this->addSql('ALTER TABLE works_likes DROP FOREIGN KEY FK_6016372BA76ED395');
        $this->addSql('DROP TABLE works_likes');
    }
}
