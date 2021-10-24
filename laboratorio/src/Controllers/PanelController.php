<?php

namespace Controllers;

use Controllers\Controller;

use App\SessionManager;
use Repositories\ExamRepository;
use Repositories\UserRepository;
use Views\PHPTemplateView;

class PanelController extends Controller 
{
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        ExamRepository $examRepository, 
        SessionManager $sessionManager,
        UserRepository $userRepository) {
        $this->examRepository = $examRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    public function index(): PHPTemplateView
    {
        $userId = $this->auth($this->sessionManager);

        $currentUser = $this->userRepository->findById($userId);

        $examCount = $this->examRepository->getExamCountByUserId($userId);

        return new PHPTemplateView('panel.php', array(
            'current_user' => $currentUser,
            'exam_count' => $examCount
        ));
    }

    public function redirectPanel() 
    {
        $this->redirect('panel', true);
    }
}