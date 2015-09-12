<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150912114508 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE turn (id INT AUTO_INCREMENT NOT NULL, leg_id INT DEFAULT NULL, player_id INT DEFAULT NULL, arrow1multiplier VARCHAR(255) NOT NULL, arrow1hit INT NOT NULL, arrow1score INT NOT NULL, arrow2multiplier VARCHAR(255) NOT NULL, arrow2hit INT NOT NULL, arrow2score INT NOT NULL, arrow3multiplier VARCHAR(255) NOT NULL, arrow3hit INT NOT NULL, arrow3score INT NOT NULL, total_score INT NOT NULL, INDEX IDX_20201547A0D74527 (leg_id), INDEX IDX_2020154799E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leg (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, INDEX IDX_75D0804FE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, board_id INT DEFAULT NULL, referee_id INT DEFAULT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, INDEX IDX_232B318C33D1A3E7 (tournament_id), INDEX IDX_232B318CE7EC5785 (board_id), INDEX IDX_232B318C4A087CA2 (referee_id), INDEX IDX_232B318CC0990423 (player1_id), INDEX IDX_232B318CD22CABCD (player2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, game_mode VARCHAR(255) NOT NULL, game_count INT NOT NULL, leg_mode VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_board (tournament_id INT NOT NULL, board_id INT NOT NULL, INDEX IDX_9900BB5E33D1A3E7 (tournament_id), INDEX IDX_9900BB5EE7EC5785 (board_id), PRIMARY KEY(tournament_id, board_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_player (tournament_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_FCB3843533D1A3E7 (tournament_id), INDEX IDX_FCB3843599E6F5DF (player_id), PRIMARY KEY(tournament_id, player_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_20201547A0D74527 FOREIGN KEY (leg_id) REFERENCES leg (id)');
        $this->addSql('ALTER TABLE turn ADD CONSTRAINT FK_2020154799E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE leg ADD CONSTRAINT FK_75D0804FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C4A087CA2 FOREIGN KEY (referee_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC0990423 FOREIGN KEY (player1_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CD22CABCD FOREIGN KEY (player2_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE tournament_board ADD CONSTRAINT FK_9900BB5E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_board ADD CONSTRAINT FK_9900BB5EE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_player ADD CONSTRAINT FK_FCB3843533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_player ADD CONSTRAINT FK_FCB3843599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE turn DROP FOREIGN KEY FK_20201547A0D74527');
        $this->addSql('ALTER TABLE leg DROP FOREIGN KEY FK_75D0804FE48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C33D1A3E7');
        $this->addSql('ALTER TABLE tournament_board DROP FOREIGN KEY FK_9900BB5E33D1A3E7');
        $this->addSql('ALTER TABLE tournament_player DROP FOREIGN KEY FK_FCB3843533D1A3E7');
        $this->addSql('DROP TABLE turn');
        $this->addSql('DROP TABLE leg');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_board');
        $this->addSql('DROP TABLE tournament_player');
    }
}
