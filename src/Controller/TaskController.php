<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TaskRepository;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $taskRepository)
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findAll()]);
    }

    /**
     * @Route("/tasks/valid", name="task_list_valid")
     */
    public function listValidAction(TaskRepository $taskRepository)
    {
        return $this->render('task/list.valid.html.twig', ['tasks' => $taskRepository->findAllCheckValidate(1) ]);
    }

    /**
     * @Route("/tasks/notvalid", name="task_list_not_valid")
     */
    public function listNotValidAction(TaskRepository $taskRepository)
    {
        return $this->render('task/list.notvalid.html.twig', ['tasks' => $taskRepository->findAllCheckValidate(0) ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request,UserRepository $userRepository)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUser($this->getUser());
            if(!$this->getUser())
            {
                $task->setUser($userRepository->findOneByUsername('anonyme'));
            }
            $task->setCreatedAt(new \DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle",methods={"GET"})
     */
    public function toggleTaskAction(Task $task,Request $request,$id)
    {
        $task->setIsDone(!$task->getIsDone());
        $this->getDoctrine()->getManager()->flush();

        if($task->getIsDone())
        {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));
        }
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task,Security $security)
    {
        $user = $security->getUser();

        if($security->isGranted("ROLE_ADMIN") === false && $user->getId() !== $task->getUser()->getId()){
            throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accéder à cette ressource");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('delete', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
