<?php


namespace AppBundle\Controller\Covid19;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SupplyController
 * @package AppBundle\Controller\Covid19
 * @Route("/covid-19/supplies")
 */
class SupplyController extends Controller
{
    /**
     * @param Request $request
     * @Route("/", name="covid19_supplies")
     */
    public function indexAction(Request $request) {
        return $this->render(
            'covid19/pages/supplies.html.twig',
            []
        );
    }

}