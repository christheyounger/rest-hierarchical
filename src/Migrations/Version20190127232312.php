<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190127232312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__store AS SELECT id, name FROM store');
        $this->addSql('DROP TABLE store');
        $this->addSql('CREATE TABLE store (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_FF575877727ACA70 FOREIGN KEY (parent_id) REFERENCES store (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO store (id, name) SELECT id, name FROM __temp__store');
        $this->addSql('DROP TABLE __temp__store');
        $this->addSql('CREATE INDEX IDX_FF575877727ACA70 ON store (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_FF575877727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__store AS SELECT id, name FROM store');
        $this->addSql('DROP TABLE store');
        $this->addSql('CREATE TABLE store (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO store (id, name) SELECT id, name FROM __temp__store');
        $this->addSql('DROP TABLE __temp__store');
    }
}
