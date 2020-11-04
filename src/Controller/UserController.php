<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileType;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $user = new Participant();
        $registerForm = $this->createForm(RegisterType::class, $user);
        $registerForm->handleRequest($request);
        if($registerForm->isSubmitted() && $registerForm->isValid()) {
            $hashed = $encoder->encodePassword($user, $user->getPass());
            $user->setPass($hashed);
            $user->setAdministrateur(0);
            $user->setActif(1);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('login');
        } else {
            $this->addFlash('error', 'Votre compte n\'a pas été enregistré.');
        }
        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView()
        ]);
    }

    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils) {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render("user/login.html.twig", [
                'last_username' => $lastUsername,
                'error'         => $error
            ]
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

    /**
     * @Route("/user/{id}", name="user_profile", requirements={"id": "\d+"})
     */
    public function showProfile(int $id, EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Participant::class);
        $user = $repo->find($id);

        return $this->render("user/profile.html.twig", ["user" => $user]);
    }

    /**
     * @Route("/user/edit", name="user_profile_edit")
     */
    public function editProfile(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()){
            $avatar = $profileForm->get('avatar')->getData();

            if ($avatar) {
                $newFilename = uniqid().'.'.$avatar->guessExtension();
                $avatar->move(
                    $this->getParameter('upload_directory') . '/original',
                    $newFilename
                );

                $simpleImage = new \claviska\SimpleImage();
                $simpleImage->fromFile($this->getParameter('upload_directory') . '/original/' . $newFilename)
                    ->bestFit(200, 200)
                    ->toFile($this->getParameter('upload_directory') . '/small/' . $newFilename);

                $user->setAvatar($newFilename);
            }

            $hashed = $encoder->encodePassword($user, $user->getPass());
            $user->setPass($hashed);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user/profile_edit.html.twig', [
            'profileForm' => $profileForm->createView()
        ]);
    }
}
