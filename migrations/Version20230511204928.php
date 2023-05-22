<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511204928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_menu_card (menu_id INT NOT NULL, menu_card_id INT NOT NULL, INDEX IDX_C71B0FA9CCD7E912 (menu_id), INDEX IDX_C71B0FA9C217879E (menu_card_id), PRIMARY KEY(menu_id, menu_card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_menu_card ADD CONSTRAINT FK_C71B0FA9CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_card ADD CONSTRAINT FK_C71B0FA9C217879E FOREIGN KEY (menu_card_id) REFERENCES menu_card (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_menu_card DROP FOREIGN KEY FK_C71B0FA9CCD7E912');
        $this->addSql('ALTER TABLE menu_menu_card DROP FOREIGN KEY FK_C71B0FA9C217879E');
        $this->addSql('DROP TABLE menu_menu_card');
    }
}
