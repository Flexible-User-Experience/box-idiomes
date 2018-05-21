<?php

namespace AppBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180207141225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_student (event_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_3274069C71F7E88B (event_id), INDEX IDX_3274069CCB944F1A (student_id), PRIMARY KEY(event_id, student_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_student ADD CONSTRAINT FK_3274069C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_student ADD CONSTRAINT FK_3274069CCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE events_students');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE events_students (event_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_595D15B171F7E88B (event_id), INDEX IDX_595D15B1CB944F1A (student_id), PRIMARY KEY(event_id, student_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events_students ADD CONSTRAINT FK_595D15B171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE events_students ADD CONSTRAINT FK_595D15B1CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('DROP TABLE event_student');
    }
}
