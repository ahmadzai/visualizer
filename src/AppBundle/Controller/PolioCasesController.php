<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\PolioCasesDatatable;
use AppBundle\Entity\PolioCases;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Poliocase controller.
 *
 * @Route("poliocases")
 * @Security("has_role('ROLE_NORMAL_USER')")
 */
class PolioCasesController extends Controller
{
    /**
     * Lists all polioCase entities.
     *
     * @Route("/", name="poliocases_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $polioCases = $em->getRepository('AppBundle:PolioCases')->findAll();

        return $this->render('poliocases/index.html.twig', array(
            'polioCases' => $polioCases,
        ));
    }

    /**
     * Creates a new polioCase entity.
     *
     * @Route("/new", name="poliocases_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $polioCase = new PolioCases();
        $form = $this->createForm('AppBundle\Form\PolioCasesType', $polioCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($polioCase);
            $em->flush();

            return $this->redirectToRoute('poliocases_show', array('id' => $polioCase->getId()));
        }

        return $this->render('poliocases/new.html.twig', array(
            'polioCase' => $polioCase,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a polioCase entity.
     *
     * @Route("/case/{id}", name="poliocases_show")
     * @Method("GET")
     */
    public function showAction(PolioCases $polioCase)
    {
        $deleteForm = $this->createDeleteForm($polioCase);

        return $this->render('poliocases/show.html.twig', array(
            'polioCase' => $polioCase,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing polioCase entity.
     *
     * @Route("/{id}/edit", name="poliocases_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PolioCases $polioCase)
    {
        $deleteForm = $this->createDeleteForm($polioCase);
        $editForm = $this->createForm('AppBundle\Form\PolioCasesType', $polioCase);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poliocases_edit', array('id' => $polioCase->getId()));
        }

        return $this->render('poliocases/edit.html.twig', array(
            'polioCase' => $polioCase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a polioCase entity.
     *
     * @Route("/{id}", name="poliocases_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PolioCases $polioCase)
    {
        $form = $this->createDeleteForm($polioCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($polioCase);
            $em->flush();
        }

        return $this->redirectToRoute('poliocases_index');
    }

    /**
     * Creates a form to delete a polioCase entity.
     *
     * @param PolioCases $polioCase The polioCase entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PolioCases $polioCase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('poliocases_delete', array('id' => $polioCase->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param $type
     * @Route("/download", name="poliocases_data_download", options={"expose"=true})
     * @Method("GET")
     * @return Response
     * @Security("has_role('ROLE_EDITOR')")
     * @throws \Exception
     */
    public function downloadAction(Request $request) {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.post');
        //$datatable->buildDatatable();

        // or use the DatatableFactory
        /** @var DatatableInterface $datatable */
        $datatable = $this->get('sg_datatables.factory')->create(PolioCasesDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $dbQueryBuilder = $responseService->getDatatableQueryBuilder();

            $qb = $dbQueryBuilder->getQb();

//            $qb->addOrderBy('polioCases.year', 'DESC');
            $qb->addOrderBy('district.province');
            $qb->addOrderBy('district.id');
            return $responseService->getResponse();
        }


        // creating buttons
        $buttons = array(
            'a' => ['route'=>'#', 'title'=>'New Record', 'class'=>'btn-info'],
        );
        return $this->render('pages/table.html.twig',
            ['datatable'=>$datatable,'title'=>'Polio Cases In Afghanistan', 'buttons' => $buttons]);
    }

    /**
     * Bulk delete action.
     *
     * @param Request $request
     *
     * @Route("/bulk/delete", name="poliocases_bulk_delete")
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
            $repository = $em->getRepository('AppBundle:PolioCases');

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
