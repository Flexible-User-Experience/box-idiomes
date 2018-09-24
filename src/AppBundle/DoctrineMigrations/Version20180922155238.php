<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180922155238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, tic VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, payment_method INT DEFAULT 0 NOT NULL, iban_for_bank_draft_payment VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_92C4739C8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spending (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, provider_id INT DEFAULT NULL, date DATE NOT NULL, description VARCHAR(255) DEFAULT NULL, base_amount DOUBLE PRECISION NOT NULL, is_payed TINYINT(1) DEFAULT NULL, payment_date DATE DEFAULT NULL, payment_method INT DEFAULT 0 NOT NULL, document VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_E44ECDD12469DE2 (category_id), INDEX IDX_E44ECDDA53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spending_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE provider ADD CONSTRAINT FK_92C4739C8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE spending ADD CONSTRAINT FK_E44ECDD12469DE2 FOREIGN KEY (category_id) REFERENCES spending_category (id)');
        $this->addSql('ALTER TABLE spending ADD CONSTRAINT FK_E44ECDDA53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE spending DROP FOREIGN KEY FK_E44ECDDA53A8AA');
        $this->addSql('ALTER TABLE spending DROP FOREIGN KEY FK_E44ECDD12469DE2');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE spending');
        $this->addSql('DROP TABLE spending_category');
    }
}
