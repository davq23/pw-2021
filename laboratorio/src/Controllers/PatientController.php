<?php

namespace Controllers;

use Controllers\Exceptions\BadRequestException;
use Domains\Patient;
use Exception;
use Repositories\PatientRepository;

class PatientController extends Controller
{
    private PatientRepository $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function index(): array
    {
        $limit = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT);
        $offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT,
            ['options' => FILTER_NULL_ON_FAILURE]);

        $limit = $limit ?? 20;
        $offset = $offset ?? 0;

        return $this->patientRepository->fetchAll($limit, $offset);
    }

    /**
     * @return Patient|null
     * @throws Exception
     */
    public function register(): ?Patient
    {
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