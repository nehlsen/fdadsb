<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150921204825 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE leg ADD winner_id INT DEFAULT NULL, DROP closed');
        $this->addSql('ALTER TABLE leg ADD CONSTRAINT FK_75D0804F5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_75D0804F5DFCD4B8 ON leg (winner_id)');
        $this->addSql('ALTER TABLE game ADD winner_id INT DEFAULT NULL, ADD legs_won_player1 INT NOT NULL, ADD legs_won_player2 INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_232B318C5DFCD4B8 ON game (winner_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5DFCD4B8');
        $this->addSql('DROP INDEX IDX_232B318C5DFCD4B8 ON game');
        $this->addSql('ALTER TABLE game DROP winner_id, DROP legs_won_player1, DROP legs_won_player2');
        $this->addSql('ALTER TABLE leg DROP FOREIGN KEY FK_75D0804F5DFCD4B8');
        $this->addSql('DROP INDEX IDX_75D0804F5DFCD4B8 ON leg');
        $this->addSql('ALTER TABLE leg ADD closed TINYINT(1) NOT NULL, DROP winner_id');
    }
}
