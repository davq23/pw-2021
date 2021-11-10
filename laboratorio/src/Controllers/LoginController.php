<?php

namespace Controllers;

use App\Exceptions\UnauthorizedRequestException;
use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;
use Views\Exceptions\InvalidViewException;
use Views\PHPTemplateView;

class LoginController extends Controller
{
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, SessionManager $sessionManager) {
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UnauthorizedRequestException
     * @throws InvalidViewException
     */
    public function loginForm(): PHPTemplateView {
        $this->auth($this->sessionManager, false);

        $message = $this->sessionManager->getFlash('message');
        $userArray = $this->sessionManager->getFlash('user_array');

        return new PHPTemplateView('login.php', array(
            'message' => $message,
            'user_array' => $userArray
        ));
    }

    /**
     * @throws UnauthorizedRequestException|BadRequestException
     */
    public function postLoginForm(): void {
        $this->auth($this->sessionManager, false);

        $user = null;
        $userArray = \filter_input_array(
            INPUT_POST,
            array(
                'username-email' => FILTER_DEFAULT,
                'password' => FILTER_DEFAULT
            ),
            true
        );

        try {
            if (filter_var($userArray['username-email'], FILTER_VALIDATE_EMAIL)) {
                $user = $this->userRepository->findByEmail($userArray['username-email']);
            } else {
                $user = $this->userRepository->findByUsername($userArray['username-email']);
            }
            if (!$user->verifyPassword($userArray['password'])) {
                throw new DomainNotFoundException();
            }
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message', 'Invalid username or password');
            $this->sessionManager->setFlash('user_array', $userArray);

            $this->redirect('login', true);
        }

        $this->sessionManager->regenerateId(true);
        $this->sessionManager->add('user_id', $user->getId());
        $this->sessionManager->add('user_role', $user->getUserRole());

        $this->redirect('panel', true);
    }

    public function logout() {
        $this->auth($this->sessionManager);

        $this->sessionManager->destroy();

        $this->redirect('login', true);
    }

}
