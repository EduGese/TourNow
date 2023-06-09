<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609160855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE scores scores INT DEFAULT NULL, CHANGE average_score average_score NUMERIC(2, 1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_activity DROP review, DROP score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity CHANGE scores scores INT DEFAULT 0, CHANGE average_score average_score NUMERIC(2, 1) DEFAULT \'0.0\'');
        $this->addSql('ALTER TABLE user_activity ADD review TEXT DEFAULT NULL, ADD score NUMERIC(5, 2) DEFAULT NULL');
    }
}
