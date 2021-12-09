<?php

namespace Controllers;

use App\SecretKeyManager;
use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Exceptions\InvalidDomainException;
use Domains\User;
use Exception;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\UserRepository;
use Views\PHPTemplateView;
use Views\View;

class SignupController extends Controller
{
    private SessionManager $sessionManager;
    private UserRepository $userRepository;
    private SecretKeyManager $secretKeyManager;

    public function __construct(
        UserRepository $userRepository,
        SessionManager $sessionManager,
        SecretKeyManager $secretKeyManager
    ) {
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
        $this->secretKeyManager = $secretKeyManager;
    }

    public function signupForm(): View {
        $this->auth($this->sessionManager, false);

        $message = $this->sessionManager->getFlash('message');
        $userArray = $this->sessionManager->getFlash('user_array');

        return new PHPTemplateView('signup.php', array(
            'message' => $message,
            'user_array' => $userArray,
            'is_secret_doctor_form' => false
        ));
    }

    public function signupDoctorForm(): View {
        $this->auth($this->sessionManager, false);

        $message = $this->sessionManager->getFlash('message');
        $userArray = $this->sessionManager->getFlash('user_array');

        return new PHPTemplateView('signup.php', array(
            'message' => $message,
            $user_array => $userArray,
            'is_secret_doctor_form' => true
        ));
    }

    private function verifySecretKey(string $secretKey, User $user): void {
        $valid = $this->secretKeyManager->verifyKey($secretKey);

        if (!$valid) {
            throw new BadRequestException('Invalid secret key');
        }

        $user->setUserRole(User::USER_ROLE_DOCTOR);
    }

    public function postSignupForm() {
        $errorMessage = null;
        $redirectRoute = 'signup';

        $this->auth($this->sessionManager, false);

        try {
            $user = User::fromArray($_POST);

            if (isset($_POST['secret_doctor_key'])) {
                $redirectRoute = 'doctors/signup';
                $user->setUserRole(User::USER_ROLE_DOCTOR);
            } else {
                $user->setUserRole(User::USER_ROLE_NURSE);
            }

            $user->validate();

            try {
                $this->userRepository->findByUsername($user->getUsername());
                throw new BadRequestException('Username is alredy taken');
            } catch (DomainNotFoundException $th) {
                try {
                    $this->userRepository->findByEmail($user->getEmail());
                    throw new BadRequestException('Email is alredy taken');
                } catch (DomainNotFoundException $th) {

                }
            }
            $user->hashPassword();

            $this->userRepository->registerUser($user);
        } catch (BadRequestException $badRequestException) {
            $errorMessage = $badRequestException->getMessage();
        } catch (InvalidDomainException $invalidDomainException) {
            $errorMessage = $invalidDomainException->getMessage();
        } catch (Exception $exception) {
            $errorMessage = 'Unknown error';
        }

        if (!$errorMessage) {
            $this->sessionManager->regenerateId(true);
            $this->sessionManager->add('user_id', $user->getId());
            $this->sessionManager->add('user_role', $user->getUserRole());
            $redirectRoute = 'panel';
        } else {
            $this->sessionManager->setFlash('message', $errorMessage);
            $this->sessionManager->setFlash('user_array', $_POST);
        }


        $this->redirect($redirectRoute, true);
    }

}
