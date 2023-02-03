<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202225914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characteristic (id INT AUTO_INCREMENT NOT NULL, height DOUBLE PRECISION NOT NULL, temp_weight DOUBLE PRECISION NOT NULL, age INT NOT NULL, sexe VARCHAR(20) NOT NULL, goal VARCHAR(50) NOT NULL, fat_rate INT NOT NULL, activity_rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user DROP height, DROP goal, DROP sexe, DROP temp_weight, DROP activity_rate, DROP fat_rate, DROP age');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE characteristic');
        $this->addSql('ALTER TABLE user ADD height DOUBLE PRECISION DEFAULT NULL, ADD goal VARCHAR(50) DEFAULT NULL, ADD sexe VARCHAR(50) DEFAULT NULL, ADD temp_weight DOUBLE PRECISION DEFAULT NULL, ADD activity_rate DOUBLE PRECISION DEFAULT NULL, ADD fat_rate INT DEFAULT NULL, ADD age INT DEFAULT NULL');
    }
}
