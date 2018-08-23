<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180823112641 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP INDEX UNIQ_3BAE0AA72DE62210, ADD INDEX IDX_3BAE0AA72DE62210 (previous_id)');
        $this->addSql('ALTER TABLE event DROP INDEX UNIQ_3BAE0AA7AA23F6C8, ADD INDEX IDX_3BAE0AA7AA23F6C8 (next_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP INDEX IDX_3BAE0AA72DE62210, ADD UNIQUE INDEX UNIQ_3BAE0AA72DE62210 (previous_id)');
        $this->addSql('ALTER TABLE event DROP INDEX IDX_3BAE0AA7AA23F6C8, ADD UNIQUE INDEX UNIQ_3BAE0AA7AA23F6C8 (next_id)');
    }
}
