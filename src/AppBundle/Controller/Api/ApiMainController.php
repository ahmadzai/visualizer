<?php
/**
 * Created by PhpStorm.
 * User: Awesome
 * Date: 1/29/2019
 * Time: 11:16 AM
 */

namespace AppBundle\Controller\Api;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiMainController
 * @package AppBundle\Controller\Api
 * @Route("api")
 * @Security("has_role('ROLE_USER')")
 */
class ApiMainController extends Controller
{
    /**
     * @Route("/region/{region}")
     * @param Request $request
     * @param string $region
     * @return Response
     */
    public function getRegionsData(Request $request, $region = "all") {

        return new JsonResponse("Hello it is working", 200);

    }


}