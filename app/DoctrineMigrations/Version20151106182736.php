<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151106182736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_player (group_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_4B9FA2B5FE54D947 (group_id), INDEX IDX_4B9FA2B599E6F5DF (player_id), PRIMARY KEY(group_id, player_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_player ADD CONSTRAINT FK_4B9FA2B5FE54D947 FOREIGN KEY (group_id) REFERENCES RoundGroup (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_player ADD CONSTRAINT FK_4B9FA2B599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE group_player');
    }
}
