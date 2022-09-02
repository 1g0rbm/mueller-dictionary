<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901132748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create words table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE words_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE words (id INT NOT NULL, source VARCHAR(255) NOT NULL, pos VARCHAR(100) NOT NULL, transcription VARCHAR(100) NOT NULL, translations JSONB NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE words_id_seq CASCADE');
        $this->addSql('DROP TABLE words');
    }
}
