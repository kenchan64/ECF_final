<?php

namespace App\Security;

use App\Entity\Admin;
use App\Entity\User;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;
    private AdminRepository $adminRepository;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UrlGeneratorInterface       $urlGenerator,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        AdminRepository             $adminRepository
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->adminRepository = $adminRepository;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function authenticate(Request $request): Passport
    {
        if ($request->attributes->get('_route') === 'app_register' && $request->isMethod('POST')) {
            $user = $this->createUser($request);
            $email = $user->getEmail();
            $password = $user->getPassword();

            return $this->createPassport($user, $email, $password, $request);
        }

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $this->userRepository->findOneByEmail($email);
        $admin = $this->adminRepository->findOneByEmail($email);

        if ($user) {
            return $this->authenticateUser($user, $password, $email, $request);
        }

        if ($admin) {
            return $this->authenticateUser($admin, $password, $email, $request);
        }

        throw new CustomUserMessageAuthenticationException('Nous n\'avons pas trouvé de compte avec cet email.');
    }

    private function createUser(Request $request): User
    {
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setDefaultGuests($request->request->get('defaultGuests'));
        $user->setAllergies($request->request->get('allergies'));
        $user->setPassword($request->request->get('plainPassword'));

        return $user;
    }
    private function authenticateUser($user, $password, $email, $request): Passport
    {
        if ($this->passwordHasher->isPasswordValid($user, $password)) {
            return $this->createPassport($user, $email, $password, $request);
        }

        throw new AuthenticationException("Mot de passe incorrect pour ce compte.");
    }

    private function createPassport($user, $email, $password, $request): Passport
    {
        return new Passport(
            new UserBadge($email, function () use ($user) {
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
            ]
        );
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') == 'app_login'
            && $request->getMethod() == 'POST';
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        // Récupérer l'utilisateur authentifié
        $user = $token->getUser();

        // Vérifier si l'utilisateur est un administrateur
        if ($user instanceof Admin) {
            // Rediriger l'administrateur vers la page d'administration
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        }

        // Rediriger les autres utilisateurs (par exemple, les utilisateurs normaux) vers une autre page
        return new RedirectResponse($this->urlGenerator->generate('app_reservation'));
    }
}
