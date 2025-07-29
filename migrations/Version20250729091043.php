<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729091043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1041E39B');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1041E39B');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
