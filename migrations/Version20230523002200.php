<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523002200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD restaurant_settings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558EFA292 FOREIGN KEY (restaurant_settings_id) REFERENCES restaurant_settings (id)');
        $this->addSql('CREATE INDEX IDX_42C849558EFA292 ON reservation (restaurant_settings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558EFA292');
        $this->addSql('DROP INDEX IDX_42C849558EFA292 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP restaurant_settings_id');
    }
}
