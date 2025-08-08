<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807123853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(80) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag_symphony (tag_id INT NOT NULL, symphony_id INT NOT NULL, PRIMARY KEY(tag_id, symphony_id))');
        $this->addSql('CREATE INDEX IDX_2A3A8732BAD26311 ON tag_symphony (tag_id)');
        $this->addSql('CREATE INDEX IDX_2A3A8732EFA9593A ON tag_symphony (symphony_id)');
        $this->addSql('ALTER TABLE tag_symphony ADD CONSTRAINT FK_2A3A8732BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag_symphony ADD CONSTRAINT FK_2A3A8732EFA9593A FOREIGN KEY (symphony_id) REFERENCES symphony (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE tag_symphony DROP CONSTRAINT FK_2A3A8732BAD26311');
        $this->addSql('ALTER TABLE tag_symphony DROP CONSTRAINT FK_2A3A8732EFA9593A');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_symphony');
    }
}
