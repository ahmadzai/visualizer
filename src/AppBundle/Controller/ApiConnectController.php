<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiConnect;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Settings;

/**
 * Apiconnect controller.
 *
 * @Route("apiconnect")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ApiConnectController extends Controller
{
    /**
     * Lists all apiConnect entities.
     *
     * @Route("/", name="apiconnect_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $apiConnects = $em->getRepository('AppBundle:ApiConnect')->findAll();

        return $this->render('apiconnect/index.html.twig', array(
            'apiConnects' => $apiConnects,
            'tableSetting' => json_encode(Settings::tableSetting())
        ));
    }

    /**
     * Creates a new apiConnect entity.
     *
     * @Route("/new", name="apiconnect_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $apiConnect = new Apiconnect();
        $apiConnect->setUser($this->getUser());
        $form = $this->createForm('AppBundle\Form\ApiConnectType', $apiConnect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiConnect);
            $em->flush();

            return $this->redirectToRoute('apiconnect_show', array('id' => $apiConnect->getId()));
        }

        return $this->render('apiconnect/new.html.twig', array(
            'apiConnect' => $apiConnect,
            'form' => $form->createView(),
            'change_breadcrumb' => true,
            'breadcrumb_text' => "Adding <small> a new API service</small>"
        ));
    }

    /**
     * Finds and displays a apiConnect entity.
     *
     * @Route("/{id}", name="apiconnect_show")
     * @Method("GET")
     * @param ApiConnect $apiConnect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(ApiConnect $apiConnect)
    {
        $deleteForm = $this->createDeleteForm($apiConnect);

        return $this->render('apiconnect/show.html.twig', array(
            'apiConnect' => $apiConnect,
            'delete_form' => $deleteForm->createView(),
            'change_breadcrumb' => true,
            'breadcrumb_text' => $apiConnect->getApiServiceName()."<small> detail</small>"
        ));
    }

    /**
     * Displays a form to edit an existing apiConnect entity.
     *
     * @Route("/{id}/edit", name="apiconnect_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param ApiConnect $apiConnect
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ApiConnect $apiConnect)
    {
        $deleteForm = $this->createDeleteForm($apiConnect);
        $editForm = $this->createForm('AppBundle\Form\ApiConnectType', $apiConnect);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('apiconnect_edit', array('id' => $apiConnect->getId()));
        }

        return $this->render('apiconnect/edit.html.twig', array(
            'apiConnect' => $apiConnect,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'change_breadcrumb' => true,
            'breadcrumb_text' => $apiConnect->getApiServiceName()."<small> edit</small>"
        ));
    }

    /**
     * Deletes a apiConnect entity.
     *
     * @Route("/{id}", name="apiconnect_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ApiConnect $apiConnect)
    {
        $form = $this->createDeleteForm($apiConnect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($apiConnect);
            $em->flush();
        }

        return $this->redirectToRoute('apiconnect_index');
    }

    /**
     * Creates a form to delete a apiConnect entity.
     *
     * @param ApiConnect $apiConnect The apiConnect entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ApiConnect $apiConnect)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('apiconnect_delete', array('id' => $apiConnect->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
