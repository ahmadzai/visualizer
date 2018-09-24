<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Datatables\CatchupDataDatatable;
use AppBundle\Service\Charts;
use AppBundle\Service\HtmlTable;
use AppBundle\Service\Settings;
use AppBundle\Service\Triangle;
use AppBundle\Service\Maps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CatchupDataController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class CatchupDataController extends Controller
{

    /**
     * @return Response
     * @Route("/catchup_data", name="catchup_data")
     */
    public function indexAction() {

        $provinces = $this->getDoctrine()
            ->getRepository('AppBundle:Province')
            ->findAll();

        return $this->render("pages/catchup_data/index.html.twig", [
            'source'=>'CatchupData',
            'url' => 'catchup_data',
            'urlCluster' => 'catchup_data_cluster',
        ]);
    }

    /**
     * @param null $district
     * @return Response
     * @Route("/catchup_data/clusters/{district}", name="catchup_data_cluster", options={"expose"=true})
     */
    public  function clusterLevelAction($district = null) {

        $data = ['district' => $district===null?0:$district];
        $data['title'] = 'Catchup Data Clusters Trends';
        $data['pageTitle'] = "Catchup Data Trends and Analysis, Cluster Level";
        $data['source'] = 'CatchupData';
        $data['ajaxUrl'] = 'catchup_data';
        return $this->render("pages/catchup_data/clusters-table.html.twig",
            $data
        );

    }

    /**
     * @param Request $request
     * @param $type
     * @Route("/catchup_data/download/{type}", name="catchup_data_download", options={"expose"=true})
     * @Method("GET")
     * @return Response
     * @Security("has_role('ROLE_EDITOR')")
     * @throws \Exception
     */
    public function downloadAction(Request $request, $type='all') {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(CatchupDataDatatable::class);
        $datatable->buildDatatable(['type'=>$type]);

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();

            $qb = $dbQueryBuilder->getQb();
            if($type != "all") {
                $qb->where("catchupdata.dataSource = :type");
                $qb->setParameter('type', $type);
            }
            $qb->addOrderBy('campaign.id', 'DESC');
            $qb->addOrderBy('district.province');
            $qb->addOrderBy('district.id');
            return $responseService->getResponse();
        }


        // creating buttons
        $buttons = array(
            'a' => ['route'=>'#', 'title'=>'New Record', 'class'=>'btn-info'],
        );
        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable,'title'=>'Catchup (Fieldbook) Data', 'buttons' => $buttons]);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete/catchup_data", name="catchup_data_bulk_delete")
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
            $repository = $em->getRepository('AppBundle:CatchupData');

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