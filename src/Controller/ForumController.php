<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Routing\Annotation\Route;



use App\Entity\Subforum;
use App\Entity\Thread;
use App\Entity\User;
use App\Entity\Message;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;

use App\Form\ThreadType;
use App\Form\MessageType;



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
    public function subforum(EntityManagerInterface $em, $subforum, Request $request, Security $security)
    {
        $user = $security->getUser();

        $sub = $em->getRepository(Subforum::class)->findBy([
           'id'=>$subforum,
        ]);


//        var_dump($sub);

        $thread = new Thread();


        $new_message = new Message();

        //THERE IS PROBABLY A BETTER WAY FOR THIS STUFF:

        $new_message->setUser($user);
        $new_message->setThread($thread);
        $new_message->setDate_created(new \DateTime());

        $thread->setDate_created(new \DateTime());
        $thread->setSubforum($sub[0]);
        $thread->setUser($user);
        $thread->addMessage($new_message);


        $form = $this->createForm(ThreadType::class,$thread);

        $form->add('submit', SubmitType::class,[
            'label'=>'SUBMIT',
        ]);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $em->getEventManager();

            //THERE IS PROBABLY A BETTER WAY FOR THIS STUFF:
            $em->persist($thread->getUser());
            $em->persist($thread->getSubforum());
            $em->persist($thread->getMessages()->get(0));
            $em->persist($thread);
            $em->flush();

            return $this->redirect($request->getUri());


        }


        $threads = $em->getRepository(Thread::class)->findBy([
            'subforum' => $subforum,

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
    public function thread(EntityManagerInterface $em,$thread, Request $request, Security $security)
    {
        $new_message = new Message();

        $new_message->setDate_created(new \DateTime());
        $thr = $em->getRepository(Thread::class)->findBy([
            'id'=>$thread]);
        $new_message->setThread($thr[0]);
        $new_message->setUser($security->getUser());

//        var_dump($_SESSION['user'][0]);
//        var_dump($new_message->getContent());

        $form = $this->createForm(MessageType::class,$new_message)->add('submit', SubmitType::class,[
                     'label'=>'SUBMIT',
                 ]);


//        var_dump($request);


          $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $em->getEventManager();
            $em->persist($new_message);
            $em->persist($new_message->getUser());
            $em->flush();

            return $this->redirect($request->getUri());


        }


        $messages = $em->getRepository(Message::class)->findBy([
            'thread' => $thread,
        ]);


        return $this->render('forum/thread.html.twig', [
            'controller_name' => 'ForumController',
            'content' => $messages,
            'form' => $form->createView(),
        ]);
    }


}