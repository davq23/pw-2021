<?php

namespace Controllers;

use App\SessionManager;
use Controllers\Controller;
use Domains\User;
use Repositories\DoctorRepository;
use Repositories\ExamRepository;
use Repositories\NurseRepository;
use Repositories\UserRepository;
use Views\PHPTemplateView;

class PanelController extends Controller
{
    private DoctorRepository $doctorRepository;
    private ExamRepository $examRepository;
    private NurseRepository $nurseRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        DoctorRepository $doctorRepository,
        ExamRepository $examRepository,
        NurseRepository $nurseRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->nurseRepository = $nurseRepository;
        $this->examRepository = $examRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    public function index(): PHPTemplateView {
        $userId = $this->auth($this->sessionManager);

        $currentUser = $this->userRepository->findById($userId);

        $roleObject = null;
        $examCount = null;

        if ($currentUser->getUserRole() === User::USER_ROLE_NURSE) {
            $roleObject = $this->nurseRepository->findByUserId($userId);
            $examCount = $this->examRepository->getExamCountByNurseId($roleObject->getId());
        } else {
            $roleObject = $this->doctorRepository->findByUserId($userId);
            $examCount = $this->examRepository->getExamCountByDoctorId($roleObject->getId());
        }


        return new PHPTemplateView('panel.php', array(
            'current_user' => $currentUser,
            'exam_count' => $examCount,
            'role_object' => $roleObject
        ));
    }

    public function redirectPanel() {
        $this->redirect('panel', true);
    }

}
