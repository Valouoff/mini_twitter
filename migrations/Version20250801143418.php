<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801143418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, tweet_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date_comment DATETIME NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BC1041E39B (tweet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tweet_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, date_liker DATE NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B31041E39B (tweet_id), INDEX IDX_AC6340B3BA9CD190 (commentaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retweet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tweet_id INT DEFAULT NULL, commentaire_id INT DEFAULT NULL, date_retweet DATE NOT NULL, INDEX IDX_45E67DB3A76ED395 (user_id), INDEX IDX_45E67DB31041E39B (tweet_id), INDEX IDX_45E67DB3BA9CD190 (commentaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tweet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, date_tweet DATETIME NOT NULL, media VARCHAR(255) DEFAULT NULL, views INT NOT NULL, INDEX IDX_3D660A3BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, profil_banierre VARCHAR(255) DEFAULT NULL, date_inscription DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_follows (follower_id INT NOT NULL, followed_id INT NOT NULL, INDEX IDX_136E9479AC24F853 (follower_id), INDEX IDX_136E9479D956F010 (followed_id), PRIMARY KEY(follower_id, followed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B31041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB31041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB3BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_follows ADD CONSTRAINT FK_136E9479D956F010 FOREIGN KEY (followed_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1041E39B');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B31041E39B');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3BA9CD190');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB3A76ED395');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB31041E39B');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB3BA9CD190');
        $this->addSql('ALTER TABLE tweet DROP FOREIGN KEY FK_3D660A3BA76ED395');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479AC24F853');
        $this->addSql('ALTER TABLE user_follows DROP FOREIGN KEY FK_136E9479D956F010');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE retweet');
        $this->addSql('DROP TABLE tweet');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_follows');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
