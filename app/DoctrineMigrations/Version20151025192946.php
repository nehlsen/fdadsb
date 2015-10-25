<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20151025192946 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tournament DROP game_mode, DROP game_count, DROP leg_mode, DROP tournament_mode, DROP tournament_count');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tournament ADD game_mode VARCHAR(255) NOT NULL, ADD game_count INT NOT NULL, ADD leg_mode VARCHAR(255) NOT NULL, ADD tournament_mode VARCHAR(255) NOT NULL, ADD tournament_count INT NOT NULL');
    }
}
