<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725073707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` CHANGE user_id user_id INT NOT NULL, CHANGE tweet_id tweet_id INT NOT NULL');
        $this->addSql('ALTER TABLE tweet CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD description LONGTEXT NOT NULL, ADD profil_pic VARCHAR(255) DEFAULT NULL, ADD profil_banierre VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `like` CHANGE user_id user_id INT DEFAULT NULL, CHANGE tweet_id tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP description, DROP profil_pic, DROP profil_banierre');
    }
}
