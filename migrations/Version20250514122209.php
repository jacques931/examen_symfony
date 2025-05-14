<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514122209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE etapes (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, descriptif VARCHAR(255) NOT NULL, consignes VARCHAR(255) NOT NULL, position INT NOT NULL, INDEX IDX_E3443E176E38C0DB (parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, dateheure DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, objet VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rendus (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, url_document VARCHAR(255) NOT NULL, date_heure DATETIME NOT NULL, INDEX IDX_A999BBADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rendus_etapes (rendus_id INT NOT NULL, etapes_id INT NOT NULL, INDEX IDX_A0739B6239035739 (rendus_id), INDEX IDX_A0739B624F5CEED2 (etapes_id), PRIMARY KEY(rendus_id, etapes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rendus_message (rendus_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_9691826939035739 (rendus_id), INDEX IDX_96918269537A1329 (message_id), PRIMARY KEY(rendus_id, message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, etapes_id INT DEFAULT NULL, intitule VARCHAR(255) NOT NULL, preseentation VARCHAR(255) NOT NULL, support VARCHAR(255) NOT NULL, nature VARCHAR(255) NOT NULL, url_document VARCHAR(255) NOT NULL, INDEX IDX_939F45444F5CEED2 (etapes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, parcours_id INT DEFAULT NULL, conseiller_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, INDEX IDX_8D93D6496E38C0DB (parcours_id), INDEX IDX_8D93D6491AC39A0D (conseiller_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE etapes ADD CONSTRAINT FK_E3443E176E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus ADD CONSTRAINT FK_A999BBADA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_etapes ADD CONSTRAINT FK_A0739B6239035739 FOREIGN KEY (rendus_id) REFERENCES rendus (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_etapes ADD CONSTRAINT FK_A0739B624F5CEED2 FOREIGN KEY (etapes_id) REFERENCES etapes (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message ADD CONSTRAINT FK_9691826939035739 FOREIGN KEY (rendus_id) REFERENCES rendus (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message ADD CONSTRAINT FK_96918269537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressource ADD CONSTRAINT FK_939F45444F5CEED2 FOREIGN KEY (etapes_id) REFERENCES etapes (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496E38C0DB FOREIGN KEY (parcours_id) REFERENCES parcours (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491AC39A0D FOREIGN KEY (conseiller_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E176E38C0DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus DROP FOREIGN KEY FK_A999BBADA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_etapes DROP FOREIGN KEY FK_A0739B6239035739
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_etapes DROP FOREIGN KEY FK_A0739B624F5CEED2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message DROP FOREIGN KEY FK_9691826939035739
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendus_message DROP FOREIGN KEY FK_96918269537A1329
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ressource DROP FOREIGN KEY FK_939F45444F5CEED2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496E38C0DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6491AC39A0D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE etapes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parcours
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendus
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendus_etapes
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendus_message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ressource
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
