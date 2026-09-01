<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260901173926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE message_contact (id INT AUTO_INCREMENT NOT NULL, object VARCHAR(255) NOT NULL, content VARCHAR(1000) NOT NULL, mail VARCHAR(280) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, difficulties LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, github_link VARCHAR(255) DEFAULT NULL, active TINYINT NOT NULL, cover_image_id INT DEFAULT NULL, INDEX IDX_2FB3D0EEE5A0E336 (cover_image_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project_techno (project_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_2E230596166D1F9C (project_id), INDEX IDX_2E23059651F3C1BC (techno_id), PRIMARY KEY (project_id, techno_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project_media_object (project_id INT NOT NULL, media_object_id INT NOT NULL, INDEX IDX_3E73D551166D1F9C (project_id), INDEX IDX_3E73D55164DE5A5 (media_object_id), PRIMARY KEY (project_id, media_object_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE techno (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, active TINYINT NOT NULL, logo_id INT DEFAULT NULL, INDEX IDX_3987EEDCF98F144A (logo_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEE5A0E336 FOREIGN KEY (cover_image_id) REFERENCES media_object (id)');
        $this->addSql('ALTER TABLE project_techno ADD CONSTRAINT FK_2E230596166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_techno ADD CONSTRAINT FK_2E23059651F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D551166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_media_object ADD CONSTRAINT FK_3E73D55164DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE techno ADD CONSTRAINT FK_3987EEDCF98F144A FOREIGN KEY (logo_id) REFERENCES media_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEE5A0E336');
        $this->addSql('ALTER TABLE project_techno DROP FOREIGN KEY FK_2E230596166D1F9C');
        $this->addSql('ALTER TABLE project_techno DROP FOREIGN KEY FK_2E23059651F3C1BC');
        $this->addSql('ALTER TABLE project_media_object DROP FOREIGN KEY FK_3E73D551166D1F9C');
        $this->addSql('ALTER TABLE project_media_object DROP FOREIGN KEY FK_3E73D55164DE5A5');
        $this->addSql('ALTER TABLE techno DROP FOREIGN KEY FK_3987EEDCF98F144A');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE message_contact');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_techno');
        $this->addSql('DROP TABLE project_media_object');
        $this->addSql('DROP TABLE techno');
        $this->addSql('DROP TABLE `user`');
    }
}
