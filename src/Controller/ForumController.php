<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;



use App\Entity\Subforum;
use App\Entity\Thread;
use App\Entity\User;
use App\Entity\Message;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ForumController extends AbstractController
{



    /**
     * @Route("/forum", name="forum")
     */
    public function index(EntityManagerInterface $em)
    {
        $subforums = $em->getRepository(Subforum::class)->findAll();

//        var_dump($subforums);

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'content' => $subforums,

        ]);
    }

    /**
     * @Route("forum/{subforum}", name="subforum_name")
     */
    public function subforum(EntityManagerInterface $em, $subforum, Request $request)
    {

        $user = new User();
        $subforum = new Subforum();

        $user->setPassword('123456');
        $user->setUsername('vatroslav');



        $data = [
            'thread' => new Thread(),
        'message' => new Message()];


        $form = $this->createFormBuilder($data)
            ->add('title', TextType::class)
            ->add('content',TextareaType::class)
            ->add('submit', SubmitType::class)->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $em->getEventManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('forum/'.$subforum);
        }

        $threads = $em->getRepository(Thread::class)->findBy([
            'subforumid' => $subforum,

        ]);

        return $this->render('forum/subforum.html.twig', [
            'controller_name' => 'ForumController',
            'content' => $threads,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("forum/{subforum}/{thread}", name="thread_name")
     */
    public function thread(EntityManagerInterface $em,$thread)
    {
        $messages = $em->getRepository(Message::class)->findBy([
            'thread' => $thread,
        ]);


        return $this->render('forum/thread.html.twig', [
            'controller_name' => 'ForumController',
            'content' => $messages,
        ]);
    }


}