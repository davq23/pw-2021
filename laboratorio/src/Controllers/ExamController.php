<?php

namespace Controllers;

use App\SessionManager;
use Repositories\ExamRepository;
use Repositories\UserRepository;
use Views\Exceptions\InvalidViewException;
use Views\PHPTemplateView;
use Views\View;

class ExamController extends Controller
{
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        ExamRepository $examRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository
    ) {
        $this->examRepository = $examRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidViewException
     */
    public function index(): View {
        $userId = $this->auth($this->sessionManager);

        $currentUser = $this->userRepository->findById($userId);
        $exams = $this->examRepository->fetchAllByUserId(20, 0, $userId);

        return new PHPTemplateView('exams.php', array(
            'exams' => $exams,
            'current_user' => $currentUser
        ));
    }

}
