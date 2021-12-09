<?php

namespace Controllers;

use App\SessionManager;
use Domains\Patient;
use Domains\User;
use Repositories\ExamRepository;
use Repositories\PatientRepository;
use Repositories\UserRepository;
use Throwable;
use Views\PHPTemplateView;
use Views\View;

/**
 * Description of PatientController
 *
 * @author davido
 */
class PatientController extends Controller
{
    private PatientRepository $patientRepository;
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        PatientRepository $patientRepository,
        ExamRepository $examRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository
    ) {
        $this->examRepository = $examRepository;
        $this->patientRepository = $patientRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    public function registerForm(): View {
        $userId = $this->auth(
            $this->sessionManager,
            true,
            0,
            array(User::USER_ROLE_NURSE)
        );

        $user = $this->userRepository->findById($userId);

        return new PHPTemplateView('register-patient.php', array(
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'current_user' => $user,
        ));
    }

    public function register() {
        $userId = $this->auth($this->sessionManager, true, 0, [User::USER_ROLE_NURSE]);

        $exam = null;

        try {
            $patient = Patient::fromArray($_POST);
            $patient->validate();

            $this->patientRepository->registerPatient($patient);

            $this->sessionManager->setFlash('message-success', 'Patient registered successfully');
        } catch (Exception $e) {
            $this->sessionManager->setFlash('message-danger', $e->getMessage());
        } catch (Throwable $th) {
            $this->sessionManager->setFlash('message-danger', $th);
        }

        $this->redirect('patients/new', true);
    }

}
