<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330162427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE database (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "table" ADD database_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "table" ADD CONSTRAINT FK_F6298F46F0AA09DB FOREIGN KEY (database_id) REFERENCES database (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F6298F46F0AA09DB ON "table" (database_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "table" DROP CONSTRAINT FK_F6298F46F0AA09DB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE database
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F6298F46F0AA09DB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "table" DROP database_id
        SQL);
    }
}
