<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function homepageAction()
    {
        return $this->render(
            'default/homepage.html.twig'
        );
    }

    public function indexAction()
    {
        return $this->redirectToRoute('app_default_homepage');
    }
}
