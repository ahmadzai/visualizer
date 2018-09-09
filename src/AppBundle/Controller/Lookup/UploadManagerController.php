<?php

namespace AppBundle\Controller\Lookup;

use AppBundle\Entity\UploadManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Service\Settings;

/**
 * Uploadmanager controller.
 *
 * @Route("uploadmanager")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UploadManagerController extends Controller
{
    /**
     * Lists all uploadManager entities.
     *
     * @Route("/", name="uploadmanager_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $uploadManagers = $em->getRepository('AppBundle:UploadManager')->findAll();

        return $this->render('uploadmanager/index.html.twig', array(
            'uploadManagers' => $uploadManagers,
            'tableSetting' => json_encode(Settings::tableSetting())
        ));
    }

    /**
     * Creates a new uploadManager entity.
     *
     * @Route("/new", name="uploadmanager_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $uploadManager = new Uploadmanager();
        $form = $this->createForm('AppBundle\Form\UploadManagerType', $uploadManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($uploadManager);
            $em->flush();

            return $this->redirectToRoute('uploadmanager_show', array('id' => $uploadManager->getId()));
        }

        return $this->render('uploadmanager/new.html.twig', array(
            'uploadManager' => $uploadManager,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a uploadManager entity.
     *
     * @Route("/{id}", name="uploadmanager_show")
     * @Method("GET")
     */
    public function showAction(UploadManager $uploadManager)
    {
        $deleteForm = $this->createDeleteForm($uploadManager);

        return $this->render('uploadmanager/show.html.twig', array(
            'uploadManager' => $uploadManager,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing uploadManager entity.
     *
     * @Route("/{id}/edit", name="uploadmanager_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UploadManager $uploadManager)
    {
        $deleteForm = $this->createDeleteForm($uploadManager);
        $editForm = $this->createForm('AppBundle\Form\UploadManagerType', $uploadManager);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('uploadmanager_edit', array('id' => $uploadManager->getId()));
        }

        return $this->render('uploadmanager/edit.html.twig', array(
            'uploadManager' => $uploadManager,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a uploadManager entity.
     *
     * @Route("/{id}", name="uploadmanager_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UploadManager $uploadManager)
    {
        $form = $this->createDeleteForm($uploadManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($uploadManager);
            $em->flush();
        }

        return $this->redirectToRoute('uploadmanager_index');
    }

    /**
     * Creates a form to delete a uploadManager entity.
     *
     * @param UploadManager $uploadManager The uploadManager entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UploadManager $uploadManager)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('uploadmanager_delete', array('id' => $uploadManager->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
