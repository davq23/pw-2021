<?php

namespace Controllers;

use App\Exceptions\UnauthorizedRequestException;
use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Exceptions\InvalidDomainException;
use Domains\User;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;
use Views\Exceptions\InvalidViewException;
use Views\PHPTemplateView;

class SignupController extends Controller
{
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, SessionManager $sessionManager) {
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    public function signupForm() {
        $this->auth($this->sessionManager, false);

        $message = $this->sessionManager->getFlash('message');
        $userArray = $this->sessionManager->getFlash('user_array');

        return new PHPTemplateView('signup.php', array(
            'message' => $message,
            'user_array' => $userArray
        ));
    }

    public function postSignupForm() {
        $this->auth($this->sessionManager, false);

        $errorMessage = null;
        $userArray = filter_input_array(
            INPUT_POST,
            array(
                'username' => FILTER_DEFAULT,
                'email' => FILTER_VALIDATE_EMAIL,
                'password' => FILTER_DEFAULT
            )
        );

        try {
            User::fromArray($userArray)->validate();

            try {
                $this->userRepository->findByUsername($userArray['username']);
                throw new BadRequestException('Username is alredy taken');
            } catch (DomainNotFoundException $th) {
                try {
                    $this->userRepository->findByEmail($userArray['email']);
                    throw new BadRequestException('Email is alredy taken');
                } catch (DomainNotFoundException $th) {

                }
            }

            $user = new User(null, $userArray['email'], $userArray['username'], $userArray['password']);
            $user->validate();
            $user->hashPassword();

            $this->userRepository->registerUser($user);
        } catch (BadRequestException $badRequestException) {
            $errorMessage = $badRequestException->getMessage();
        } catch (InvalidDomainException $invalidDomainException) {
            $errorMessage = $invalidDomainException->getMessage();
        } catch (Exception $exception) {
            $errorMessage = 'Unknown error';
        }

        if (isset($errorMessage)) {
            $this->sessionManager->setFlash('message', $errorMessage);
            $this->sessionManager->setFlash('user_array', $userArray);

            $this->redirect('signup', true);
        }

        $this->sessionManager->regenerateId(true);
        $this->sessionManager->add('user_id', $user->getId());

        $this->redirect('panel', true);
    }

}
