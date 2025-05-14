<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514141112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE rendus_message (rendus_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_9691826939035739 (rendus_id), INDEX IDX_96918269537A1329 (message_id), PRIMARY KEY(rendus_id, message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message ADD CONSTRAINT FK_9691826939035739 FOREIGN KEY (rendus_id) REFERENCES rendus (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message ADD CONSTRAINT FK_96918269537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message_rendus DROP FOREIGN KEY FK_9EBE26A239035739
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message_rendus DROP FOREIGN KEY FK_9EBE26A2537A1329
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message_rendus
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE message_rendus (rendus_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_9EBE26A239035739 (rendus_id), INDEX IDX_9EBE26A2537A1329 (message_id), PRIMARY KEY(rendus_id, message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message_rendus ADD CONSTRAINT FK_9EBE26A239035739 FOREIGN KEY (rendus_id) REFERENCES rendus (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message_rendus ADD CONSTRAINT FK_9EBE26A2537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message DROP FOREIGN KEY FK_9691826939035739
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message DROP FOREIGN KEY FK_96918269537A1329
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendus_message
        SQL);
    }
}
