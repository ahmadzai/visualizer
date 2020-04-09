<?php


namespace AppBundle\Controller\Covid19;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class C4DController
 * @package AppBundle\Controller\Covid19
 * @Route("/covid-19/c4d")
 */
class C4DController extends Controller
{
    /**
     * @param Request $request
     * @Route("/", name="covid19_c4d")
     */
    public function indexAction(Request $request) {
        return $this->render(
            'covid19/pages/c4d.html.twig',
            []
        );
    }

}