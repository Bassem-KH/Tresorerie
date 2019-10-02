<?php

namespace Ositel\TresorerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OsitelTresorerieBundle:Default:index.html.twig');
    }
}
