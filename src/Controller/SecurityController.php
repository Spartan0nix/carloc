<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthRegisterType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="auth_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('auth_account');
        }

        // get the login error if there is one
        // $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername]);
    }

    /**
     * @Route("/nouveauCompte", name="auth_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, UrlGeneratorInterface $router): Response
    {
        $user = new User();
        $form = $this->createForm(AuthRegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){            
            $data = $form->getData();
            $password = $encoder->encodePassword($user, $data->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('auth_register_login', [
                'email' => $user->getEmail(),
                'password' => sha1($password)
            ]);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/register/authenticate", name="auth_register_login", methods={"GET"})
     */
    public function registerLogin(Request $request, UserRepository $repository, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator): Response {
        $credentials = [
            'email' => $request->query->get('email'),
            'password' => $request->query->get('password')
        ];

        $user = $repository->findOneBy(['email' => $credentials['email']]);
        if(!$user){
            $this->addFlash('error', "Ce compte ne semble pas exister.");
            return $this->redirectToRoute('auth_register');
        }

        if(sha1($user->getPassword()) != $credentials['password']) {
            $this->addFlash('error', "Mot de passe incorrect.");
            return $this->redirectToRoute('auth_login');
        }

        return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
            $user, 
            $request, 
            $loginFormAuthenticator, 
            'main'
        ) 
        ? $this->redirectToRoute('auth_account') 
        : $this->redirectToRoute('auth_login');
    }

    /**
     * @Route("/logout", name="auth_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/compte/details", name="auth_account")
     */
    public function account()
    {
        $user = $this->getUser();
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'last_name' => $user->getLastName(),
            'first_name' => $user->getFirstName(),
            'address' => $user->getAddress(),
            'city' => json_encode([
                'id' => $user->getCityId()->getId(),
                'name' => $user->getCityId()->getName(),
                'code' => $user->getCityId()->getCode(),
            ]),
            'department' => json_encode([
                'id' => $user->getDepartmentId()->getId(),
                'name' => $user->getDepartmentId()->getName(),
                'code' => $user->getDepartmentId()->getCode(),
                ])
            ];
        
        return $this->render('security/account.html.twig', [
            'user' => $data
        ]);
    }
}
