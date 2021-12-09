<?php

namespace Controllers;

use App\SessionManager;
use Domains\Exam;
use Domains\Exceptions\InvalidDomainException;
use Domains\User;
use Reports\ExamReport;
use Repositories\DoctorRepository;
use Repositories\ExamRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\NurseRepository;
use Repositories\PatientRepository;
use Repositories\UserRepository;
use Throwable;
use Utils\MailSender;
use Views\Exceptions\InvalidViewException;
use Views\PHPTemplateView;
use Views\ReportView;
use Views\View;

class ExamController extends Controller
{
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;
    private DoctorRepository $doctorRepository;
    private NurseRepository $nurseRepository;
    private PatientRepository $patientRepository;
    private MailSender $mailSender;

    public function __construct(
        DoctorRepository $doctorRepository,
        ExamRepository $examRepository,
        NurseRepository $nurseRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository,
        PatientRepository $patientRepository,
        MailSender $mailSender
    ) {
        $this->examRepository = $examRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
        $this->doctorRepository = $doctorRepository;
        $this->mailSender = $mailSender;
        $this->patientRepository = $patientRepository;
        $this->nurseRepository = $nurseRepository;
    }

    /**
     * @throws InvalidViewException
     */
    public function index(): View {
        $userId = $this->auth($this->sessionManager);

        $currentUser = $this->userRepository->findById($userId);
        $exams = $this->examRepository->fetchAll(20, 0, $userId);

        return new PHPTemplateView('exams.php', array(
            'exams' => $exams,
            'current_user' => $currentUser,
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'message-danger' => $this->sessionManager->getFlash('message-danger')
        ));
    }

    public function getReport(): View {
        $this->auth($this->sessionManager);

        $examId = filter_input(INPUT_GET, 'id');

        try {
            $exam = $this->examRepository->fetchById($examId);
        } catch (DomainNotFoundException $domainNotFoundException) {
            http_response_code(404);
            exit();
        }

        return new ReportView(new ExamReport($exam));
    }

    public function registerForm(): View {
        $userId = $this->auth($this->sessionManager, true, 0, [User::USER_ROLE_NURSE]);

        $currentUser = $this->userRepository->findById($userId);

        $patients = $this->patientRepository->fetchAll();

        return new PHPTemplateView('register_exam.php', array(
            'current_user' => $currentUser,
            'patients' => $patients,
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'message-success' => $this->sessionManager->getFlash('message-success')
        ));
    }

    public function register() {
        $userId = $this->auth($this->sessionManager, true, 0, [User::USER_ROLE_NURSE]);

        $exam = null;

        try {
            $nurse = $this->nurseRepository->findByUserId($userId);

            $exam = Exam::fromArray($_POST);
            $exam->setNurseId($nurse->getId());
            $exam->validate();

            $this->examRepository->registerExam($exam);

            $this->sessionManager->setFlash('message-success', 'Exam registered successfully');
        } catch (Exception $e) {
            $this->sessionManager->setFlash('message-danger', $e->getMessage());
        } catch (Throwable $th) {
            $this->sessionManager->setFlash('message-danger', $th);
        }

        $this->redirect('exams/new');
    }

    public function resultsForm() {
        $userId = $this->auth($this->sessionManager, true, 0, [User::USER_ROLE_DOCTOR]);

        $exam = null;
        $user = null;

        try {
            $user = $this->userRepository->findById($userId);
            $exam = $this->examRepository->fetchById(filter_input(INPUT_GET, 'exam_id'));
        } catch (DomainNotFoundException $domainNotFound) {
            $this->redirect('exams');
        }

        return new PHPTemplateView('register_exam_results.php', array(
            'exam' => $exam,
            'current_user' => $user,
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'message-danger' => $this->sessionManager->getFlash('message-danger')
        ));
    }

    public function registerResults() {
        $this->auth($this->sessionManager, true, 0, [User::USER_ROLE_DOCTOR]);

        $exam = null;

        try {
            $exam = $this->examRepository->fetchById(filter_input(INPUT_POST, 'exam_id'));

            $patient = $this->patientRepository->findById($exam->getPatientId());

            $exam->setResults(filter_input(INPUT_POST, 'results'));
            $exam->validate();

            $this->examRepository->registerResults($exam);

            $this->sessionManager->setFlash('message-success', 'Exam results registered successfully');

            $examReport = new ExamReport($exam);

            $this->mailSender->setTo($patient->getEmail());
            $this->mailSender->setSubject("RESULTS FOR EXAM (" . $exam->getDatetime() . ")");
            $this->mailSender->addAttachment($examReport->Output('S'));
            $this->mailSender->setMessage('Here are your results, ' . $patient->getSurnames() . ' ' . $patient->getFamilyNames());
            if ($this->mailSender->send()) {
                $this->sessionManager->setFlash('message-success', 'Exam results registered successfully and sent by email');
            } else {
                $this->sessionManager->setFlash('message-danger', 'Email could not be sent');
            }
        } catch (DomainNotFoundException $domainNotFound) {
            $this->redirect('exams');
        } catch (InvalidDomainException $invalidDomain) {
            $this->sessionManager->setFlash('message-danger', $invalidDomain->getMessage());
        } catch (Throwable $th) {
            $this->sessionManager->setFlash('message-danger', $th);
        }

        $this->redirect('exams');
    }

    public function mailExamPDF() {
        $this->auth($this->sessionManager);

        $examId = filter_input(INPUT_GET, 'exam');

        if (!$examId) {
            $this->sessionManager->setFlash('message-danger', 'Invalid exam');
        } else {

            try {
                $exam = $this->examRepository->fetchById($examId);
                $patient = $this->patientRepository->findById($exam->getPatientId());
                $examReport = new ExamReport($exam);

                $this->mailSender->setTo($patient->getEmail());
                $this->mailSender->setSubject("RESULTS FOR EXAM (" . $exam->getDatetime() . ")");
                $this->mailSender->setMessage('Here are your results, ' . $patient->getSurnames() . ' ' . $patient->getFamilyNames());
                $this->mailSender->addAttachment($examReport->Output('S'));

                if ($this->mailSender->send()) {
                    $this->sessionManager->setFlash('message-success', 'Email sent');
                } else {
                    $this->sessionManager->setFlash('message-danger', 'Email could not be sent');
                }
            } catch (DomainNotFoundException $domainNotFoundException) {
                $this->sessionManager->setFlash('message-danger', $domainNotFoundException);
            } catch (Throwable $th) {
                $this->sessionManager->setFlash('message-danger', $th);
            }
        }
        $this->redirect('exams');
    }

}
