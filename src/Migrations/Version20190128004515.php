<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Swap out foreign key for path
 */
final class Version20190128004515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Swap out foreign key for path';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_FF575877727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__store AS SELECT id, name FROM store');
        $this->addSql('DROP TABLE store');
        $this->addSql('CREATE TABLE store (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, path VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO store (id, name) SELECT id, name FROM __temp__store');
        $this->addSql('DROP TABLE __temp__store');
        $this->addSql('CREATE INDEX path_idx ON store (path)');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX path_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__store AS SELECT id, name FROM store');
        $this->addSql('DROP TABLE store');
        $this->addSql('CREATE TABLE store (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, parent_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO store (id, name) SELECT id, name FROM __temp__store');
        $this->addSql('DROP TABLE __temp__store');
        $this->addSql('CREATE INDEX IDX_FF575877727ACA70 ON store (parent_id)');
    }
}
