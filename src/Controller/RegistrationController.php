<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;

use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\HttpFoundation\File\Exception\FileException;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();


        $form = $this->createForm(RegistrationFormType::class, $user);

        $user->setDateJoined(new \DateTime());
        $user->setEmail($form->get('email')->getName());

        $user->setRoles(['FORUM_USER']);
        $user->setLastLogin(new \DateTime());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $file = $form->get('image')->getData();


            $filename= md5(uniqid()) . '.'. $file->guessExtension();

            $user->setImage($filename);

            try{
                $file->move(
                    'img',
                    $filename

                );
            } catch (FileException $e) {
                var_dump($e);
            }

//            exit(-1);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )


            );



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
