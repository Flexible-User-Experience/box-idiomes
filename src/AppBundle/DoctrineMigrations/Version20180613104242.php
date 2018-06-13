<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180613104242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE receipt (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, person_id INT DEFAULT NULL, date DATE DEFAULT NULL, is_payed TINYINT(1) DEFAULT NULL, payment_date DATE DEFAULT NULL, is_sended TINYINT(1) DEFAULT NULL, send_date DATE DEFAULT NULL, base_amount DOUBLE PRECISION DEFAULT NULL, discount_applied TINYINT(1) DEFAULT NULL, month INT NOT NULL, year INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_5399B645CB944F1A (student_id), INDEX IDX_5399B645217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt_line (id INT AUTO_INCREMENT NOT NULL, receipt_id INT DEFAULT NULL, student_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, units DOUBLE PRECISION NOT NULL, price_unit DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_476F8F7A2B5CA896 (receipt_id), INDEX IDX_476F8F7ACB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE receipt_line ADD CONSTRAINT FK_476F8F7A2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id)');
        $this->addSql('ALTER TABLE receipt_line ADD CONSTRAINT FK_476F8F7ACB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE receipt_line DROP FOREIGN KEY FK_476F8F7A2B5CA896');
        $this->addSql('DROP TABLE receipt');
        $this->addSql('DROP TABLE receipt_line');
    }
}
