<?php

namespace Fda\TournamentBundle\Engine\Setup;

interface InputInterface
{
    /**
     * get a human readable label for the implementing mode
     *
     * @return string
     */
    public function getModeLabel();
}
