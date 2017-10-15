<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 8/26/2017
 * Time: 11:20 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UsersController extends Controller
{

    /**
     * @Route("/list_all_users", name="list_all_users")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {

        $userManager = $this->container->get('fos_user.user_manager');
        $allUsers = $userManager->findUsers();
        return $this->render('pages/users.html.twig', ['users'=>$allUsers]);
    }


    /**
     * @param Request $request
     * @param User $user
     * @Route("/users/{id}/edit", name="users_edit")
     * @return Response
     */
    public function editAction(Request $request, User $user) {
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if($editForm->isValid()) {
            $this->container->get('fos_user.user_manager')->updateUser($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'User has been successfully updated!');
            return $this->redirectToRoute('list_all_users');
        }

        return $this->render('pages/user_edit.html.twig', array('user' => $user, 'edit_form' => $editForm->createView()));
    }


}