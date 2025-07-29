<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723131938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD user_id INT DEFAULT NULL, ADD tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA76ED395 ON commentaire (user_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC1041E39B ON commentaire (tweet_id)');
        $this->addSql('ALTER TABLE `like` ADD user_id INT DEFAULT NULL, ADD tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B31041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3A76ED395 ON `like` (user_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B31041E39B ON `like` (tweet_id)');
        $this->addSql('ALTER TABLE retweet ADD user_id INT DEFAULT NULL, ADD tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retweet ADD CONSTRAINT FK_45E67DB31041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_45E67DB3A76ED395 ON retweet (user_id)');
        $this->addSql('CREATE INDEX IDX_45E67DB31041E39B ON retweet (tweet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1041E39B');
        $this->addSql('DROP INDEX IDX_67F068BCA76ED395 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC1041E39B ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP user_id, DROP tweet_id');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B31041E39B');
        $this->addSql('DROP INDEX IDX_AC6340B3A76ED395 ON `like`');
        $this->addSql('DROP INDEX IDX_AC6340B31041E39B ON `like`');
        $this->addSql('ALTER TABLE `like` DROP user_id, DROP tweet_id');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB3A76ED395');
        $this->addSql('ALTER TABLE retweet DROP FOREIGN KEY FK_45E67DB31041E39B');
        $this->addSql('DROP INDEX IDX_45E67DB3A76ED395 ON retweet');
        $this->addSql('DROP INDEX IDX_45E67DB31041E39B ON retweet');
        $this->addSql('ALTER TABLE retweet DROP user_id, DROP tweet_id');
    }
}
