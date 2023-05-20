<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520173507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id_activity INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, activity_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tickets INT DEFAULT NULL, start_ubication VARCHAR(255) DEFAULT NULL, end_ubication VARCHAR(255) DEFAULT NULL, price NUMERIC(5, 2) DEFAULT NULL, UNIQUE INDEX UNIQ_AC74095A5DE7A594 (activity_name), INDEX IDX_AC74095A6B3CA4B (id_user), PRIMARY KEY(id_activity)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A6B3CA4B');
        $this->addSql('DROP TABLE activity');
    }
}
