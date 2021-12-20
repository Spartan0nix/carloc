<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'auth_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $flash;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UrlGeneratorInterface $urlGenerator, 
        CsrfTokenManagerInterface $csrfTokenManager, 
        UserPasswordEncoderInterface $passwordEncoder, 
        FlashBagInterface $flash,
        SessionInterface $session
        )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->flash = $flash;
        $this->session = $session;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            $this->flash->add('warning', 'Erreur lors de la résolution de la requête.');
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            $this->flash->add('error','Aucun compte ne semble utiliser cette adresse mail.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $check = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        if(!$check){
            $this->flash->add('error', 'Mot de passe incorrect');
        }
        return $check;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
        //     dump($targetPath);
        //     return new RedirectResponse($targetPath);
        // }

        if($this->session->get('redirect')) {
            $redirect = $this->session->get('redirect');
            $from = $redirect['from'];
            $params = isset($redirect['param']) ? $redirect['param'] : [];

            $this->session->remove('redirect');
            return new RedirectResponse($this->urlGenerator->generate($from, $params));
        }

        return new RedirectResponse($this->urlGenerator->generate('auth_account'));
    }


    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
