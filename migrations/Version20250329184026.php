<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329184026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE field (id SERIAL NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5BF545587E3C61F9 ON field (owner_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "table" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD CONSTRAINT FK_5BF545587E3C61F9 FOREIGN KEY (owner_id) REFERENCES "table" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP CONSTRAINT FK_5BF545587E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE field
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "table"
        SQL);
    }
}
