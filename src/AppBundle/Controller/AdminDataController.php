<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Datatables\AdminDataDatatable;
use AppBundle\Entity\AdminData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Importer;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AdminDataController extends Controller
{

    /**
     * @Route("/admin_data/download", name="admin_data_download", options={"expose"=true})
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(AdminDataDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();

            $qb = $dbQueryBuilder->getQb();
            $qb->addOrderBy('campaign.id', 'DESC');
            $qb->addOrderBy('district.province');
            $qb->addOrderBy('district.id');
            return $responseService->getResponse();
        }



        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable,'title'=>'Coverage (Admin) Data','add_new' => true,
             'add_new_title' => 'New Record', 'add_new_route' => '#']);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete/admin_data", name="admin_data_bulk_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($isAjax) {
            $choices = $request->request->get('data');
            $token = $request->request->get('token');

            if (!$this->isCsrfTokenValid('multiselect', $token)) {
                throw new AccessDeniedException('The CSRF token is invalid.');
            }

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:AdminData');

            foreach ($choices as $choice) {
                $entity = $repository->find($choice['id']);
                $em->remove($entity);
            }

            $em->flush();

            return new Response('Success', 200);
        }

        return new Response('Bad Request', 400);
    }




}