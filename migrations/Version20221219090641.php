<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219090641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE works ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE works ADD CONSTRAINT FK_F6E50243A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F6E50243A76ED395 ON works (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE works DROP FOREIGN KEY FK_F6E50243A76ED395');
        $this->addSql('DROP INDEX IDX_F6E50243A76ED395 ON works');
        $this->addSql('ALTER TABLE works DROP user_id');
    }
}
