<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $newTask = new task();

        $form = $this->createForm(TaskType::class, $newTask);
        $form->handleRequest($request);

        $UserRepository = $this->getDoctrine()
            ->getRepository(User::class);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $UserRepository->find($request->request->get('userId'));

            $newTask = $form->getData();
            $newTask->setCategorieId($user);
            $newTask->setCreatedAt(new \DateTime());

            $entityManager->persist($newTask);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $tasks = $this->TaskRepository = $this->getDoctrine()
            ->getRepository(Task::class)->findAll();
        $users = $UserRepository->findAll();

        return $this->render('users/index.html.twig', [
            'tasks' => $tasks,
            'taskForm' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/task/remove/{id}", name="removeTask")
     */
    public function remove($id, EntityManagerInterface $entityManager){

        $task = $this->TaskRepository = $this->getDoctrine()
            ->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('task');
    }



}
