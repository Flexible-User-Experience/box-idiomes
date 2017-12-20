<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171215111821 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, person_id INT DEFAULT NULL, date DATE NOT NULL, is_payed TINYINT(1) NOT NULL, payment_date DOUBLE PRECISION NOT NULL, base_amount DOUBLE PRECISION NOT NULL, tax_parcentage DOUBLE PRECISION DEFAULT \'0\' NOT NULL, discount_applied TINYINT(1) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, month INT NOT NULL, year INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_90651744CB944F1A (student_id), INDEX IDX_90651744217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE invoice');
    }
}
