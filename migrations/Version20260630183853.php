<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260630183853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_media_object (project_id INT NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY (project_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_3E73D551166D1F9C ON project_media_object (project_id)');
        $this->addSql('CREATE INDEX IDX_3E73D55164DE5A5 ON project_media_object (media_object_id)');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D551166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D55164DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD cover_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEE5A0E336 FOREIGN KEY (cover_image_id) REFERENCES media_object (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEE5A0E336 ON project (cover_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_media_object DROP CONSTRAINT FK_3E73D551166D1F9C');
        $this->addSql('ALTER TABLE project_media_object DROP CONSTRAINT FK_3E73D55164DE5A5');
        $this->addSql('DROP TABLE project_media_object');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EEE5A0E336');
        $this->addSql('DROP INDEX IDX_2FB3D0EEE5A0E336');
        $this->addSql('ALTER TABLE project DROP cover_image_id');
    }
}
