<?php

namespace Fda\DsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FdaDsbBundle:Default:index.html.twig', array());
    }
}
