<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add Stores table!
 */
final class Version20190127214431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Stores table';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf(
          $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
          'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('
CREATE TABLE store (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf(
          $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
          'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('DROP TABLE store');
    }
}
