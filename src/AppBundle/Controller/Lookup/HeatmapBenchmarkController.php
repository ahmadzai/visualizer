<?php

namespace AppBundle\Controller\Lookup;

use AppBundle\Entity\HeatmapBenchmark;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Heatmapbenchmark controller.
 *
 * @Route("heatmap/benchmark")
 */
class HeatmapBenchmarkController extends Controller
{
    /**
     * Lists all heatmapBenchmark entities.
     *
     * @Route("/", name="heatmap_benchmark_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $heatmapBenchmarks = $em->getRepository('AppBundle:HeatmapBenchmark')->findAll();

        return $this->render('heatmapbenchmark/index.html.twig', array(
            'heatmapBenchmarks' => $heatmapBenchmarks,
        ));
    }

    /**
     * Creates a new heatmapBenchmark entity.
     *
     * @Route("/new", name="heatmap_benchmark_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $heatmapBenchmark = new Heatmapbenchmark();
        $form = $this->createForm('AppBundle\Form\HeatmapBenchmarkType', $heatmapBenchmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heatmapBenchmark);
            $em->flush();

            return $this->redirectToRoute('heatmap_benchmark_index');
        }

        return $this->render('heatmapbenchmark/new.html.twig', array(
            'heatmapBenchmark' => $heatmapBenchmark,
            'form' => $form->createView(),
            'change_breadcrumb' => true,
            'breadcrumb_text' => "Adding <small> new heatmap rule</small>"
        ));
    }

//    /**
//     * Finds and displays a heatmapBenchmark entity.
//     *
//     * @Route("/{id}", name="heatmap_benchmark_show")
//     * @Method("GET")
//     */
//    public function showAction(HeatmapBenchmark $heatmapBenchmark)
//    {
//        $deleteForm = $this->createDeleteForm($heatmapBenchmark);
//
//        return $this->render('heatmapbenchmark/show.html.twig', array(
//            'heatmapBenchmark' => $heatmapBenchmark,
//            'delete_form' => $deleteForm->createView(),
//            'change_breadcrumb' => true,
//            'breadcrumb_text' => "Heatmap rule <small> for ".$heatmapBenchmark->getDataSource()." ".$heatmapBenchmark->getIndicator()."</small>"
//        ));
//    }

    /**
     * Displays a form to edit an existing heatmapBenchmark entity.
     *
     * @Route("/{id}/edit", name="heatmap_benchmark_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, HeatmapBenchmark $heatmapBenchmark)
    {
        $deleteForm = $this->createDeleteForm($heatmapBenchmark);
        $editForm = $this->createForm('AppBundle\Form\HeatmapBenchmarkType', $heatmapBenchmark);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heatmap_benchmark_edit', array('id' => $heatmapBenchmark->getId()));
        }

        return $this->render('heatmapbenchmark/edit.html.twig', array(
            'heatmapBenchmark' => $heatmapBenchmark,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'change_breadcrumb' => true,
            'breadcrumb_text' => "Editing <small> heatmap rule for ".$heatmapBenchmark->getDataSource()." ".$heatmapBenchmark->getIndicator()."</small>"
        ));
    }

    /**
     * Deletes a heatmapBenchmark entity.
     *
     * @Route("/{id}", name="heatmap_benchmark_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, HeatmapBenchmark $heatmapBenchmark)
    {
        $form = $this->createDeleteForm($heatmapBenchmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($heatmapBenchmark);
            $em->flush();
        }

        return $this->redirectToRoute('heatmap_benchmark_index');
    }

    /**
     * Creates a form to delete a heatmapBenchmark entity.
     *
     * @param HeatmapBenchmark $heatmapBenchmark The heatmapBenchmark entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HeatmapBenchmark $heatmapBenchmark)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('heatmap_benchmark_delete', array('id' => $heatmapBenchmark->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
