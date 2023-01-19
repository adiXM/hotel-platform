<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104214534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon_class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amenity_room_type (amenity_id INT NOT NULL, room_type_id INT NOT NULL, INDEX IDX_51D1F1829F9F1305 (amenity_id), INDEX IDX_51D1F182296E3073 (room_type_id), PRIMARY KEY(amenity_id, room_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, room_type_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, room_number INT NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_729F519BD7DED995 (room_number), INDEX IDX_729F519B296E3073 (room_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amenity_room_type ADD CONSTRAINT FK_51D1F1829F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amenity_room_type ADD CONSTRAINT FK_51D1F182296E3073 FOREIGN KEY (room_type_id) REFERENCES room_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B296E3073 FOREIGN KEY (room_type_id) REFERENCES room_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amenity_room_type DROP FOREIGN KEY FK_51D1F1829F9F1305');
        $this->addSql('ALTER TABLE amenity_room_type DROP FOREIGN KEY FK_51D1F182296E3073');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B296E3073');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE amenity_room_type');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_type');
    }
}
