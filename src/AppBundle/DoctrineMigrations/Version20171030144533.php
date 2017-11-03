<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171030144533 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank ADD CONSTRAINT FK_D860BF7A727ACA70 FOREIGN KEY (parent_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D860BF7A727ACA70 ON bank (parent_id)');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1761AD8D010');
        $this->addSql('DROP INDEX IDX_34DCD1761AD8D010 ON person');
        $this->addSql('ALTER TABLE person DROP students_id');
        $this->addSql('ALTER TABLE student ADD parent_id INT DEFAULT NULL, ADD bank_id INT DEFAULT NULL, ADD dni VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, DROP own_mobile, DROP contact_phone, DROP contact_name, DROP bank_account_number, DROP contact_dni, DROP parent_address, DROP bank_account_name');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33727ACA70 FOREIGN KEY (parent_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3311C8FB41 FOREIGN KEY (bank_id) REFERENCES bank (id)');
        $this->addSql('CREATE INDEX IDX_B723AF33727ACA70 ON student (parent_id)');
        $this->addSql('CREATE INDEX IDX_B723AF3311C8FB41 ON student (bank_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank DROP FOREIGN KEY FK_D860BF7A727ACA70');
        $this->addSql('DROP INDEX UNIQ_D860BF7A727ACA70 ON bank');
        $this->addSql('ALTER TABLE bank DROP parent_id');
        $this->addSql('ALTER TABLE person ADD students_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1761AD8D010 FOREIGN KEY (students_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_34DCD1761AD8D010 ON person (students_id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33727ACA70');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3311C8FB41');
        $this->addSql('DROP INDEX IDX_B723AF33727ACA70 ON student');
        $this->addSql('DROP INDEX IDX_B723AF3311C8FB41 ON student');
        $this->addSql('ALTER TABLE student ADD own_mobile VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_phone VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD bank_account_number VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD contact_dni VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD parent_address VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD bank_account_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP parent_id, DROP bank_id, DROP dni, DROP phone');
    }
}
