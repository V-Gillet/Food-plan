<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201181322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, origin VARCHAR(100) DEFAULT NULL, lipid INT NOT NULL, carb INT NOT NULL, protein INT NOT NULL, calories INT DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, is_recipe TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, meal_id INT DEFAULT NULL, INDEX IDX_974D05BDA76ED395 (user_id), INDEX IDX_974D05BD639666D6 (meal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal_user ADD CONSTRAINT FK_974D05BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meal_user ADD CONSTRAINT FK_974D05BD639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal_user ADD is_favourite TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_user DROP FOREIGN KEY FK_974D05BDA76ED395');
        $this->addSql('ALTER TABLE meal_user DROP FOREIGN KEY FK_974D05BD639666D6');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE meal_user');
        $this->addSql('ALTER TABLE meal_user DROP is_favourite');
    }
}
