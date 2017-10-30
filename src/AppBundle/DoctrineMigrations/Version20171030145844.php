<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171030145844 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank DROP FOREIGN KEY FK_D860BF7A727ACA70');
        $this->addSql('DROP INDEX UNIQ_D860BF7A727ACA70 ON bank');
        $this->addSql('ALTER TABLE bank CHANGE parent_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank ADD CONSTRAINT FK_D860BF7A217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D860BF7A217BBB47 ON bank (person_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bank DROP FOREIGN KEY FK_D860BF7A217BBB47');
        $this->addSql('DROP INDEX UNIQ_D860BF7A217BBB47 ON bank');
        $this->addSql('ALTER TABLE bank CHANGE person_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bank ADD CONSTRAINT FK_D860BF7A727ACA70 FOREIGN KEY (parent_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D860BF7A727ACA70 ON bank (parent_id)');
    }
}
