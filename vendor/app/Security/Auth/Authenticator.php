<?php

namespace App\Vendor\Security\Auth;

use App\Entity\User\User;
use App\Vendor\Database\Entity\AbstractEntity;
use App\Vendor\Database\Entity\User\UserRepository;
use App\Vendor\Form\LoginForm;
use App\Vendor\Http\Request\Request;
use App\Vendor\Http\Response\RedirectResponse;
use App\Vendor\Http\Session\Session;

class Authenticator
{

    private $userRepository;
    private $session;

    public function __construct(
        UserRepository $userRepository,
        Session $session
    ) {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    public function loginAction(Request $request) {
        $user = new User();
        $form = new LoginForm();
        $form->handleRequest($request, $user);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $password = $user->getPlainPassword();
                $user = $this->userRepository->findByEmail($user->getEmail());

                if (!$user instanceof AbstractEntity) {
                    $this->session->set('errors', 'User is not found!');
                    return new RedirectResponse('/login');
                }

                self::login($this->session, $user->getArrayCopy(), $password, $user->getPassword());

                return new RedirectResponse('/');
            } catch (\RuntimeException $exception) {
                $this->session->set('errors', $exception->getMessage());
            }
        }

        return new RedirectResponse('/login');
    }

    public function logoutAction(Request $request) {
        Session::destroy();
        return new RedirectResponse('/');
    }

    public static function login(Session $session, array $user, $password, $hash) {
        $isVerified = password_verify(hash('sha512', $password, true), $hash);

        if (!$isVerified) {
            throw new \RuntimeException('Password is not valid!');
        }

        $session->set('is_granted', Session::id(true));
        $session->set('user', json_encode($user, JSON_UNESCAPED_UNICODE));
    }

}
