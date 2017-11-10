<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171110143446 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE events_students (event_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_595D15B171F7E88B (event_id), INDEX IDX_595D15B1CB944F1A (student_id), PRIMARY KEY(event_id, student_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events_students ADD CONSTRAINT FK_595D15B171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE events_students ADD CONSTRAINT FK_595D15B1CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE event ADD previous_id INT DEFAULT NULL, ADD next_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA72DE62210 FOREIGN KEY (previous_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AA23F6C8 FOREIGN KEY (next_id) REFERENCES event (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA72DE62210 ON event (previous_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7AA23F6C8 ON event (next_id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3371F7E88B');
        $this->addSql('DROP INDEX IDX_B723AF3371F7E88B ON student');
        $this->addSql('ALTER TABLE student DROP event_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE events_students');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA72DE62210');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7AA23F6C8');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA72DE62210 ON event');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7AA23F6C8 ON event');
        $this->addSql('ALTER TABLE event DROP previous_id, DROP next_id');
        $this->addSql('ALTER TABLE student ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_B723AF3371F7E88B ON student (event_id)');
    }
}
