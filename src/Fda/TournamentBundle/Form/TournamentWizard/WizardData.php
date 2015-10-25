<?php

namespace Fda\TournamentBundle\Form\TournamentWizard;

use Fda\BoardBundle\Entity\Board;
use Fda\PlayerBundle\Manager\PlayerManager;
use Fda\TournamentBundle\Engine\Setup\PresetSimple;
use Fda\TournamentBundle\Entity\Tournament;

class WizardData
{
    // the 'small' setup
    const PRESET_SIMPLE = 'simple';
    // the 'big' setup
    const PRESET_FULL   = 'full';
    // every player once against each other, may take couple of days or even weeks
    const PRESET_LEAGUE = 'league';

    const FILL_GROUPS_RANDOM = 'random';
    const FILL_GROUPS_MANUAL = 'manual';

    /** @var string name of the tournament */
    protected $name = '';

    /** @var string */
    protected $preset = self::PRESET_SIMPLE;

    /** @var Board[] */
    protected $boards = array();

    /** @var int number of groups */
    protected $numberOfGroups = 2;

    /** @var string how to fill the groups */
    protected $fillGroups = self::FILL_GROUPS_MANUAL;

    /** @var array player-ID => group (int index or 'none') */
    protected $players = array();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * @param string $preset
     */
    public function setPreset($preset)
    {
        $this->preset = $preset;
    }

    public static function getAllPresets()
    {
        return array(
            self::PRESET_SIMPLE,
            self::PRESET_FULL,
            self::PRESET_LEAGUE,
        );
    }

    /**
     * @return Board[]
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * @param Board[] $boards
     */
    public function setBoards($boards)
    {
        $this->boards = $boards;
    }

    /**
     * @return int
     */
    public function getNumberOfGroups()
    {
        return $this->numberOfGroups;
    }

    /**
     * @param int $numberOfGroups
     */
    public function setNumberOfGroups($numberOfGroups)
    {
        $this->numberOfGroups = $numberOfGroups;
    }

    /**
     * @return string
     */
    public function getFillGroups()
    {
        return $this->fillGroups;
    }

    /**
     * @param string $fillGroups
     */
    public function setFillGroups($fillGroups)
    {
        $this->fillGroups = $fillGroups;
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param array $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    public function getPlayerIdsGrouped()
    {
        $grouped = array();

        foreach ($this->players as $playerId => $association) {
            if (null !== $association) {
                $grouped[(int)$association][] = $playerId;
            }
        }

        return $grouped;
    }

    public function getPlayerIds()
    {
        $playerIds = array();

        foreach ($this->players as $playerId => $association) {
            if (null !== $association) {
                $playerIds[] = $playerId;
            }
        }

        return $playerIds;
    }

    /**
     * @param PlayerManager $playerManager to resolve player-IDs
     * @return Tournament
     * @throws \Exception
     */
    public function createTournament(PlayerManager $playerManager)
    {
        if ($this->preset == self::PRESET_SIMPLE) {
            $setup = PresetSimple::create($this->getPlayerIdsGrouped());
            // TODO implement missing presets
//        } elseif ($this->preset == self::PRESET_FULL) {
//            $setup = PresetFull::create($this->numberOfGroups);
//        } elseif ($this->preset == self::PRESET_LEAGUE) {
//            $setup = PresetLeague::create($this->numberOfGroups);
        } else {
            throw new \Exception('TODO');
        }

        $tournament = new Tournament();
        $tournament->setName($this->name);
        $tournament->setSetup($setup);
        $tournament->setBoards($this->boards);

        $players = array();
        foreach ($this->getPlayerIds() as $playerId) {
            $players[] = $playerManager->getPlayer($playerId);
        }
        $tournament->setPlayers($players);

        return $tournament;
    }
}
