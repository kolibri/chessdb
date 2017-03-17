<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
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
