<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301124329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clan ADD CONSTRAINT FK_9FF6A30CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9FF6A30CA76ED395 ON clan (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan DROP FOREIGN KEY FK_9FF6A30CA76ED395');
        $this->addSql('DROP INDEX IDX_9FF6A30CA76ED395 ON clan');
        $this->addSql('ALTER TABLE clan DROP user_id');
    }
}
