<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index()
    {
       // $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);
        return $this->render('index.html.twig', ['tasks'=>$tasks]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        $title=trim($request->request->get('title'));

        if(empty($title)){
            return $this->redirectToRoute('to_do_list');
        }
       
       $entityManager=$this->getDoctrine()->getManager();
       $task = new Task;
       $task->setTitle($title);
       $task->setStatus(false);
       $entityManager->persist($task);
       $entityManager->flush();
       return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/switchStatus/{id}", name="switchStatus")
     */
    public function switchStatus($id)
    {
        $em=$this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);
        if($task->getStatus()==false){
            $task->setStatus(! $task->getStatus());
            $em->flush();
        }
        return $this->redirectToRoute('to_do_list');
    }

       /**
     * @Route("/delete/{id}", name="deleteTask")
     */
    public function delete(Task $id)
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('to_do_list');
    }
}