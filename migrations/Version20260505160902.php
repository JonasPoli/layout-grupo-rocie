<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505160902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_reviews (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, author_name VARCHAR(255) NOT NULL, author_location VARCHAR(100) DEFAULT NULL, rating INT NOT NULL, title VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, verified TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, reviewed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B8A9F0BF4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_reviews ADD CONSTRAINT FK_B8A9F0BF4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products ADD about_items LONGTEXT DEFAULT NULL, ADD rating_average NUMERIC(3, 1) DEFAULT NULL, ADD rating_count INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_reviews DROP FOREIGN KEY FK_B8A9F0BF4584665A');
        $this->addSql('DROP TABLE product_reviews');
        $this->addSql('ALTER TABLE products DROP about_items, DROP rating_average, DROP rating_count');
    }
}
