<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716175836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notifications (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, type VARCHAR(50) NOT NULL, message LONGTEXT NOT NULL, channel VARCHAR(10) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_notification_preferences (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT NOT NULL, notification_types JSON NOT NULL, channel VARCHAR(10) NOT NULL, frequency VARCHAR(20) NOT NULL, language VARCHAR(10) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE user_notification_preferences');
    }
}
