<?php

namespace Oidnus\WalletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OidnusWalletBundle:Default:index.html.twig');
    }
}
