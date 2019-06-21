<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;



class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/own", name="profile_own")
     * @Method({"PUT"})
     */
    public function view_own(Request $request, UserPasswordEncoderInterface $encoder, GuardAuthenticatorHandler $authenticatorHandler)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManagerForClass(User::class);

//        $user = $entityManager->getRepository(User::class)->findBy(
//            ['username'=> $username])[0];



//        var_dump($user);

        $form = $this->createForm(ProfileType::class, $user,[
            'method'=>'PUT'])->add('submit', SubmitType::class,[
            'label' => 'Edit profile',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // encode the plain password
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('image')->getData();
            if($form->get('password')->getData()) {
                $user->setPassword(
                    $encoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }

            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $user->setImage($filename);
            $user->setUsername($form->get('username')->getData());
            $user->setEmail($form->get('email')->getData());

            try {
                $file->move(
                    'img',
                    $filename

                );
            } catch (FileException $e) {
                var_dump($e);
            }

            $em->persist($user);
            $em->flush();


        }
        var_dump($form->isSubmitted());

        return $this->render('profile/viewown.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/user/{username}", name="profile_user")
     */

    public function view_profile(EntityManagerInterface $em, $username){
        $user = $em->getRepository(User::class)->findOneBy([
            'username'=> $username
        ]);

        return $this->render('profile/viewprofile.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }


}
