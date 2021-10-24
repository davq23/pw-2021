<?php

namespace Controllers;

use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Patient;
use Exception;
use Repositories\ExamRepository;
use Repositories\PatientRepository;

class PatientController extends Controller
{
    private PatientRepository $patientRepository;
    private ExamRepository $examRepository;
    private SessionManager $sessionManager;

    public function __construct(
        PatientRepository $patientRepository,
        ExamRepository $examRepository,
        SessionManager $sessionManager
    ) {
        $this->examRepository = $examRepository;
        $this->patientRepository = $patientRepository;
        $this->sessionManager = $sessionManager;
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

    /**
     * @return Patient
     * @throws Exception
     */
    public function register(): Patient
    {
        $this->auth($this->sessionManager, true, Controller::AJAX_REQUEST);

        $body = $this->getJsonBody();

        if ($body === false) {
            throw new BadRequestException("Invalid body");
        }

        $patient = Patient::fromArray($body);
        $patient->validate();

        $this->patientRepository->registerPatient($patient);

        return $patient;
    }
}