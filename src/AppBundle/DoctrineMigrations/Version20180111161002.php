<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111161002 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student ADD tariff_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3392348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('CREATE INDEX IDX_B723AF3392348FD2 ON student (tariff_id)');
        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207DCB944F1A');
        $this->addSql('DROP INDEX IDX_9465207DCB944F1A ON tariff');
        $this->addSql('ALTER TABLE tariff DROP student_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3392348FD2');
        $this->addSql('DROP INDEX IDX_B723AF3392348FD2 ON student');
        $this->addSql('ALTER TABLE student DROP tariff_id');
        $this->addSql('ALTER TABLE tariff ADD student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207DCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_9465207DCB944F1A ON tariff (student_id)');
    }
}
