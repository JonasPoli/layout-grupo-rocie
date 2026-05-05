<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504201011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banners (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, desktop_image VARCHAR(255) DEFAULT NULL, mobile_image VARCHAR(255) DEFAULT NULL, button_text VARCHAR(255) DEFAULT NULL, button_url VARCHAR(500) DEFAULT NULL, secondary_button_text VARCHAR(255) DEFAULT NULL, secondary_button_url VARCHAR(500) DEFAULT NULL, display_page VARCHAR(50) DEFAULT NULL, sort_order INT DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brands (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, official_website VARCHAR(255) DEFAULT NULL, sort_order INT DEFAULT NULL, show_on_home TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_7EA24434989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, short_description LONGTEXT DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, icon VARCHAR(100) DEFAULT NULL, sort_order INT DEFAULT NULL, show_on_home TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_3AF34668989D9B62 (slug), INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_messages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, document VARCHAR(30) DEFAULT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(30) DEFAULT NULL, whatsapp VARCHAR(30) DEFAULT NULL, state VARCHAR(2) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, subject VARCHAR(100) DEFAULT NULL, department VARCHAR(100) DEFAULT NULL, message LONGTEXT NOT NULL, status VARCHAR(30) NOT NULL, form_type VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_faqs (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, question LONGTEXT NOT NULL, answer LONGTEXT NOT NULL, sort_order INT DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_4DC9C444584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_images (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, alt_text VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, is_main TINYINT(1) NOT NULL, sort_order INT DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8263FFCE4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variations (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(255) NOT NULL, sku VARCHAR(100) DEFAULT NULL, ean VARCHAR(100) DEFAULT NULL, color VARCHAR(100) DEFAULT NULL, size VARCHAR(100) DEFAULT NULL, model VARCHAR(100) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C8D400754584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, main_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, internal_code VARCHAR(100) DEFAULT NULL, sku VARCHAR(100) DEFAULT NULL, ean VARCHAR(100) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, full_description LONGTEXT DEFAULT NULL, summary LONGTEXT DEFAULT NULL, benefits LONGTEXT DEFAULT NULL, differentials LONGTEXT DEFAULT NULL, usage_indication LONGTEXT DEFAULT NULL, material VARCHAR(255) DEFAULT NULL, composition VARCHAR(255) DEFAULT NULL, dimensions VARCHAR(255) DEFAULT NULL, weight VARCHAR(100) DEFAULT NULL, color VARCHAR(100) DEFAULT NULL, size VARCHAR(100) DEFAULT NULL, capacity VARCHAR(100) DEFAULT NULL, packaging VARCHAR(255) DEFAULT NULL, warranty VARCHAR(255) DEFAULT NULL, origin VARCHAR(100) DEFAULT NULL, active TINYINT(1) NOT NULL, is_featured TINYINT(1) NOT NULL, is_new TINYINT(1) NOT NULL, is_promotional TINYINT(1) NOT NULL, sort_order INT DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description LONGTEXT DEFAULT NULL, main_image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 (slug), INDEX IDX_B3BA5A5A44F5D008 (brand_id), INDEX IDX_B3BA5A5AC6C55574 (main_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_categories (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A99419434584665A (product_id), INDEX IDX_A994194312469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_related (product_source INT NOT NULL, product_target INT NOT NULL, INDEX IDX_B18E6B203DF63ED7 (product_source), INDEX IDX_B18E6B2024136E58 (product_target), PRIMARY KEY(product_source, product_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representatives (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, state VARCHAR(2) NOT NULL, city VARCHAR(100) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, whatsapp VARCHAR(30) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE showrooms (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, state VARCHAR(2) NOT NULL, city VARCHAR(100) NOT NULL, neighborhood VARCHAR(150) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, number VARCHAR(20) DEFAULT NULL, complement VARCHAR(100) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, whatsapp VARCHAR(30) DEFAULT NULL, email VARCHAR(150) DEFAULT NULL, opening_hours LONGTEXT DEFAULT NULL, google_maps_url VARCHAR(500) DEFAULT NULL, main_image VARCHAR(255) DEFAULT NULL, sort_order INT DEFAULT NULL, active TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE product_faqs ADD CONSTRAINT FK_4DC9C444584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_images ADD CONSTRAINT FK_8263FFCE4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variations ADD CONSTRAINT FK_C8D400754584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A44F5D008 FOREIGN KEY (brand_id) REFERENCES brands (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AC6C55574 FOREIGN KEY (main_category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A99419434584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_categories ADD CONSTRAINT FK_A994194312469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_related ADD CONSTRAINT FK_B18E6B203DF63ED7 FOREIGN KEY (product_source) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_related ADD CONSTRAINT FK_B18E6B2024136E58 FOREIGN KEY (product_target) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE product_faqs DROP FOREIGN KEY FK_4DC9C444584665A');
        $this->addSql('ALTER TABLE product_images DROP FOREIGN KEY FK_8263FFCE4584665A');
        $this->addSql('ALTER TABLE product_variations DROP FOREIGN KEY FK_C8D400754584665A');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A44F5D008');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AC6C55574');
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A99419434584665A');
        $this->addSql('ALTER TABLE product_categories DROP FOREIGN KEY FK_A994194312469DE2');
        $this->addSql('ALTER TABLE product_related DROP FOREIGN KEY FK_B18E6B203DF63ED7');
        $this->addSql('ALTER TABLE product_related DROP FOREIGN KEY FK_B18E6B2024136E58');
        $this->addSql('DROP TABLE banners');
        $this->addSql('DROP TABLE brands');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE contact_messages');
        $this->addSql('DROP TABLE product_faqs');
        $this->addSql('DROP TABLE product_images');
        $this->addSql('DROP TABLE product_variations');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE product_categories');
        $this->addSql('DROP TABLE product_related');
        $this->addSql('DROP TABLE representatives');
        $this->addSql('DROP TABLE showrooms');
        $this->addSql('DROP TABLE users');
    }
}
