<?php

namespace Controllers;

use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Exceptions\InvalidDomainException;
use Domains\Nurse;
use Exception;
use Repositories\ExamRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\NurseRepository;
use Repositories\UserRepository;
use Throwable;
use Views\PHPTemplateView;

class NurseController extends Controller
{
    private NurseRepository $nurseRepository;
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        NurseRepository $nurseRepository,
        ExamRepository $examRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository
    ) {
        $this->examRepository = $examRepository;
        $this->nurseRepository = $nurseRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function index(): array {
        $this->auth($this->sessionManager);

        $limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?? 20;
        $offset = filter_input(
                INPUT_GET,
                'offset',
                FILTER_VALIDATE_INT,
                ['options' => FILTER_NULL_ON_FAILURE]
            ) ?? 0;

        return $this->nurseRepository->fetchAll($limit, $offset);
    }

    public function registerForm(): PHPTemplateView {
        $userId = $this->auth($this->sessionManager);

        $user = $this->userRepository->findById($userId);
        $nurse = null;

        try {
            $nurse = $this->nurseRepository->findByUserId($userId);
        } catch (DomainNotFoundException $domainNotFoundException) {

        }

        return new PHPTemplateView('register-nurse.php', array(
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'current_user' => $user,
            'current_nurse' => $nurse
        ));
    }

    /**
     * @throws Exception
     */
    public function register() {
        $userId = $this->auth($this->sessionManager);

        try {
            $this->nurseRepository->findByUserId($userId);

            $this->sessionManager->setFlash('message-danger', 'User already has nurse info');
            $this->redirect('nurse/register', true);
        } catch (DomainNotFoundException $domainNotFoundException) {

        }

        try {
            $nurse = Nurse::fromArray($_POST);
            $nurse->setUserId($userId);
            $nurse->validate();
            $this->nurseRepository->registerNurse($nurse);
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $this->redirect('nurse/register', true);
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unexpected error');
            $this->redirect('nurse/register', true);
        }

        $this->sessionManager->setFlash('message-success', 'Nurse info successfully registered');
        $this->redirect('nurse', true);
    }

    public function update() {
        $userId = $this->auth($this->sessionManager);

        try {
            $oldnurse = $this->nurseRepository->findByUserId($userId);

            $newnurse = Nurse::fromArray($_POST);
            $newnurse->validate();

            $newnurse->setId($oldnurse->getId());
            $this->nurseRepository->updateNurse($newnurse);

            $this->sessionManager->setFlash('message-success', 'Nurse info successfully updated');
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'User has no nurse info');
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
        } catch (Throwable $th) {
            error_log($th);
            $this->sessionManager->setFlash('message-danger', 'Unexpected error');
        }

        $this->redirect('nurses/register/', true);
    }

}
