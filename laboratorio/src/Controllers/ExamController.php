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
    public function index(): View
    {
        $userId = $this->auth($this->sessionManager);

        $exams = $this->examRepository->fetchAll(100, 0);
        $currentUser = $this->userRepository->findById($userId);
        
        return new PHPTemplateView('exams.php', array(
            'exams' => $exams,
            'current_user' => $currentUser
        ));
    }
}