<?php

namespace AppBundle\Controller;

use AppBundle\Form\ImportPgnType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/import")
 */
class ImportController extends Controller
{
    /**
     * @Route("/pgn", name="import")
     */
    public function pgnAction(Request $request)
    {
        $form = $this->createForm(ImportPgnType::class);

        $form->handleRequest($request);

        return $this->render(
            'import/pgn.html.twig',
            ['form' => $form->createView()]
        );
    }
}
