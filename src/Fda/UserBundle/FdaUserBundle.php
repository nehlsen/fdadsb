<?php

namespace Fda\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FdaUserBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
