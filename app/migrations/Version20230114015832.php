<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114015832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_type_media (room_type_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_50D256E7296E3073 (room_type_id), INDEX IDX_50D256E7EA9FDD75 (media_id), PRIMARY KEY(room_type_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE room_type_media ADD CONSTRAINT FK_50D256E7296E3073 FOREIGN KEY (room_type_id) REFERENCES room_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_type_media ADD CONSTRAINT FK_50D256E7EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_type_media DROP FOREIGN KEY FK_50D256E7296E3073');
        $this->addSql('ALTER TABLE room_type_media DROP FOREIGN KEY FK_50D256E7EA9FDD75');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE room_type_media');
    }
}
