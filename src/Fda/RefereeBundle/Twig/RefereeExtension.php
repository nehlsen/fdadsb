<?php

namespace Fda\RefereeBundle\Twig;

use Fda\RefereeBundle\Ledger\Ledger;

class RefereeExtension extends \Twig_Extension
{
    /** @var Ledger */
    protected $ledger;

    /**
     * @param Ledger $ledger
     */
    public function setLedger($ledger)
    {
        $this->ledger = $ledger;
    }

    /**
     * {@InheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'ledger' => new \Twig_Function_Method($this, 'getLedger', array(
//                'is_safe' => array('html'),
//                'needs_environment' => true
            )),
        );
    }

    /**
     * @return Ledger
     */
    public function getLedger()
    {
        return $this->ledger;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'referee';
    }
}
