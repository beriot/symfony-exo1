<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $users = new user();
        $form = $this->createForm(UsersType::class, $users);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setInscriptionDate(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
        }

        $usersRepositoy = $this->getDoctrine()
        ->getRepository(user::class)
        ->findAll();


        return $this->render('users/index.html.twig', [
            'Users' => $usersRepositoy,
            'UserForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/user/{id}", name="user")
     */

    public function indexUser($id,Request $request, EntityManagerInterface $entityManager ){

        $users = $this->getDoctrine()
                    ->getRepository(user::class)->find($id);
        
        $form = $this->createForm(UsersType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
        }
        
        $userRepositoy = $this->getDoctrine()
        ->getRepository(user::class)
        ->findAll();
        dump($userRepositoy);


        return $this->render('users/User.html.twig', [
            'User'=>$userRepositoy,
            'userUpdate' => $users,
            'UserForm' => $form->createView()
        ]);
    }
}
