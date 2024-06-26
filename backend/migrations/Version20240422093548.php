<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240422093548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create table chat';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, title VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_user (chat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2B0F4B081A9A7125 (chat_id), INDEX IDX_2B0F4B08A76ED395 (user_id), PRIMARY KEY(chat_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B081A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B08A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD chat_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F1A9A7125 ON message (chat_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE chat_user DROP FOREIGN KEY FK_2B0F4B081A9A7125');
        $this->addSql('ALTER TABLE chat_user DROP FOREIGN KEY FK_2B0F4B08A76ED395');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_user');
        $this->addSql('DROP INDEX IDX_B6BD307F1A9A7125 ON message');
        $this->addSql('ALTER TABLE message DROP chat_id');
    }
}
