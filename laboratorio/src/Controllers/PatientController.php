<?php

namespace Controllers;

use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Exceptions\InvalidDomainException;
use Domains\Patient;
use Exception;
use Repositories\ExamRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\PatientRepository;
use Repositories\UserRepository;
use Views\PHPTemplateView;

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

    /**
     * @return array
     * @throws Exception
     */
    public function index(): array
    {
        $this->auth($this->sessionManager);

        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT);
        $offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT,
            ['options' => FILTER_NULL_ON_FAILURE]);

        $limit = $limit ?? 20;
        $offset = $offset ?? 0;

        return $this->patientRepository->fetchAll($limit, $offset);
    }

    public function registerForm() : PHPTemplateView
    {
        $userId = $this->auth($this->sessionManager);

        $user = $this->userRepository->findById($userId);

        try {            
            if ($this->patientRepository->findByUserId($userId)) {
                $this->redirect('panel');
            }
        } catch (\Throwable $th) {
        }

        return new PHPTemplateView('register-patient.php', array(
            'current_user' => $user,
        ));
    }

    /**
     * @throws Exception
     */
    public function register(): void
    {
        $userId = $this->auth($this->sessionManager);

        try {
            $this->patientRepository->findByUserId($userId);
            
            $this->sessionManager->setFlash('message-danger', 'User already has patient info');
            $this->redirect('patient/register', true);
        } catch (DomainNotFoundException $domainNotFoundException) {}

        try {
            $patient = Patient::fromArray($_POST);
            $patient->validate();
            $this->patientRepository->registerPatient($patient);
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $this->redirect('patient/register', true);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unexpected error');
            $this->redirect('patient/register', true);
        }

        $this->sessionManager->setFlash('message-success', 'Patient info successfully registered');
        $this->redirect('patient', true);
    }

    public function update(): void
    {
        $userId = $this->auth($this->sessionManager);

        try {
            $oldPatient = $this->patientRepository->findByUserId($userId);

            $newPatient = Patient::fromArray($_POST);
            $newPatient->validate();
            
            $newPatient->setId($oldPatient->getId());
            $this->patientRepository->updatePatient($newPatient);
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'User has no patient info');
            $this->redirect('patient/register', true);
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $this->redirect('patient/update', true);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unexpected error');
            $this->redirect('patient/update', true);
        }

        $this->sessionManager->setFlash('message-success', 'Patient info successfully updated');
        $this->redirect('patient', true);
    }
}