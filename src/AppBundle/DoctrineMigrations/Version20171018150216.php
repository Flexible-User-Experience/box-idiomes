<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171018150216 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student ADD surname VARCHAR(255) NOT NULL, ADD birth_date DATE DEFAULT NULL, ADD contact_phone VARCHAR(255) DEFAULT NULL, ADD contact_name VARCHAR(255) DEFAULT NULL, ADD banc_account_number VARCHAR(255) NOT NULL, DROP brith_date, DROP phone_contact, DROP name_contact, CHANGE own_mobile own_mobile VARCHAR(255) DEFAULT NULL, CHANGE payment payment INT DEFAULT 0 NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student ADD brith_date DATE NOT NULL, ADD phone_contact VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD name_contact VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP surname, DROP birth_date, DROP contact_phone, DROP contact_name, DROP banc_account_number, CHANGE own_mobile own_mobile VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE payment payment DOUBLE PRECISION DEFAULT NULL');
    }
}
