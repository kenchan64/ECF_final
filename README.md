# ECF_final
 
 Connection Administrateur du site:
 -Identifiant: ga.sus@hotmail.fr
 -Mot de passe: SharkWater64
 
 SQL base de données en ligne de commande avec php bin/console make:entity :
 
 $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
 
 $this->addSql('ALTER TABLE gallery ADD photo_filename VARCHAR(255) NOT NULL');
 
 $this->addSql('CREATE TABLE opening_hours (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(255) NOT NULL, hours TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
 
 $this->addSql('ALTER TABLE gallery ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_size INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
 
 $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');  //je l'ai supprimée mais je le referais prochainement.
 
 $this->addSql('DROP TABLE user');
 
 $this->addSql('CREATE TABLE menu_menu_card (menu_id INT NOT NULL, menu_card_id INT NOT NULL, INDEX IDX_C71B0FA9CCD7E912 (menu_id), INDEX IDX_C71B0FA9C217879E (menu_card_id), PRIMARY KEY(menu_id, menu_card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_menu_card ADD CONSTRAINT FK_C71B0FA9CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_menu_card ADD CONSTRAINT FK_C71B0FA9C217879E FOREIGN KEY (menu_card_id) REFERENCES menu_card (id) ON DELETE CASCADE');
        
        $this->addSql('ALTER TABLE opening_hours ADD midday_open TIME DEFAULT NULL, ADD midday_close TIME DEFAULT NULL, ADD evening_open TIME DEFAULT NULL, ADD evening_close TIME DEFAULT NULL');
        
$this->addSql('ALTER TABLE opening_hours ADD closed TINYINT(1) DEFAULT NULL');
$this->addSql('ALTER TABLE reservation ADD max_guests INT NOT NULL');

$this->addSql('CREATE TABLE restaurant_settings (id INT AUTO_INCREMENT NOT NULL, max_guests INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

$this->addSql('ALTER TABLE reservation ADD restaurant_settings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558EFA292 FOREIGN KEY (restaurant_settings_id) REFERENCES restaurant_settings (id)');
        $this->addSql('CREATE INDEX IDX_42C849558EFA292 ON reservation (restaurant_settings_id)');
