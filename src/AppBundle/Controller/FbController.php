<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Fieldbook;
use AppBundle\Entity\FieldbookEntry;
use AppBundle\Form\FieldbookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class MainController
 * @package AppBundle\Controller
 * @Security("has_role('ROLE_USER')")
 */
class FbController extends Controller
{

    /**
     * Using a table instead of a div
     *
     * @Route("/fb", name="fb_entry")
     * @Template()
     */
    public function indexAction(Request $request)
    {
//        $discounts = new DiscountCollection();
//
//        $discounts->setProductName('Mug');
//
//        // 5% discount when buying from 5 to 10 items
//        $discountA = new Discount();
//        $discountA->setQuantityFrom(5);
//        $discountA->setQuantityTo(10);
//        $discountA->setDiscount(5);
//        $discounts->getDiscounts()->add($discountA);
//
//        // 10% discount when buying from 11 to 25 items
//        $discountB = new Discount();
//        $discountB->setQuantityFrom(11);
//        $discountB->setQuantityTo(25);
//        $discountB->setDiscount(10); // 10%
//        $discounts->getDiscounts()->add($discountB);
//
//        // 20% discount when buying from 26 to 99 items
//        $discountC = new Discount();
//        $discountC->setQuantityFrom(26);
//        $discountC->setQuantityTo(99);
//        $discountC->setDiscount(20); // 20%
//        $discounts->getDiscounts()->add($discountC);

//        $repo = $this->getDoctrine()->getRepository('AppBundle:Fieldbook');
//
//        $data = null;

        $repo = $this->getDoctrine()->getRepository('AppBundle:Fieldbook');
        //$id = $repo->getMaxId();
        $fieldbook = $repo->findOneBy(['id' => $repo->getMaxId()]);
        $fieldbook->setUser($this->getUser());

        $form = $this->createForm(FieldbookType::class, $fieldbook);
        $form->handleRequest($request);
        if($form->get('save')->isClicked() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entries = $form->get('fieldbookEntries')->getData();
            if(count($entries) < count($fieldbook->getFieldbookEntries())) {

               $orgEntries = $fieldbook->getFieldbookEntries();
               foreach ($orgEntries as $orgEntry) {
                   $found = false;
                   foreach ($entries as $entry) {
                       if($entry->getId() === $orgEntry->getId()) {
                           $found = true;
                           break;
                       }
                   }
                   if(!$found)
                       $fieldbook->removeFieldbookEntries($orgEntry);

               }
            }

            $em->persist($fieldbook);
            $em->flush();
        }


        //$form->get('save')->isClicked() && $form->isValid() && $repo->save($data);


//        return $this->render('p' [
//            'form' => $form->createView(),
//            'data' => $fieldBooks,
//        ]);

        return $this->render(':pages/fieldbook:fb-entry.html.twig',
                [
                    'form' => $form->createView(),
                    'data' => $fieldbook,
                ]
            );
    }


}