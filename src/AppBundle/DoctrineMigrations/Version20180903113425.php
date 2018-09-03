<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180903113425 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice ADD is_sepa_xml_generated TINYINT(1) DEFAULT NULL, ADD sepa_xml_generated_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD is_sepa_xml_generated TINYINT(1) DEFAULT NULL, ADD sepa_xml_generated_date DATE DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice DROP is_sepa_xml_generated, DROP sepa_xml_generated_date');
        $this->addSql('ALTER TABLE receipt DROP is_sepa_xml_generated, DROP sepa_xml_generated_date');
    }
}
