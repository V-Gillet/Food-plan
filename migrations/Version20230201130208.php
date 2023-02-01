<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201130208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE need (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, lipid INT DEFAULT NULL, carb INT DEFAULT NULL, protein INT DEFAULT NULL, maintenance_calory INT DEFAULT NULL, gain_calory INT DEFAULT NULL, loss_calory INT DEFAULT NULL, UNIQUE INDEX UNIQ_E6F46C44A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE need ADD CONSTRAINT FK_E6F46C44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE need DROP FOREIGN KEY FK_E6F46C44A76ED395');
        $this->addSql('DROP TABLE need');
    }
}
