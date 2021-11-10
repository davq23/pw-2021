<?php

namespace Controllers;

use App\SessionManager;
use Controllers\Exceptions\BadRequestException;
use Domains\Doctor;
use Domains\Exceptions\InvalidDomainException;
use Domains\User;
use Repositories\DoctorRepository;
use Repositories\Exceptions\DomainNotFoundException;
use Views\PHPTemplateView;
use Views\View;

/**
 * Description of DoctorController
 *
 * @author davido
 */
class DoctorController extends Controller
{
    private SessionManager $sessionManager;
    private DoctorRepository $doctorRepository;

    public function __construct(
        SessionManager $sessionManager,
        DoctorRepository $doctorRepository
    ) {
        $this->sessionManager = $sessionManager;
        $this->doctorRepository = $doctorRepository;
    }

    public function register() {
        $userId = $this->auth(
            $this->sessionManager,
            true,
            0,
            array(User::USER_ROLE_DOCTOR)
        );

        $doctor = null;

        try {
            try {
                $this->doctorRepository->findByUserId($userId);
                throw BadRequestException('Doctor info already defined');
            } catch (DomainNotFoundException $domainNotFoundException) {

            }

            $doctor = Doctor::fromArray($_POST);
            $doctor->validate();

            $doctor = $this->doctorRepository->registerDoctor($doctor);

            $this->sessionManager->setFlash('message-success', 'Doctor info registered');
        } catch (BadRequestException $badRequestException) {
            $this->sessionManager->setFlash('message-danger', $badRequestException->getMessage());
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash(
                'message-danger',
                $invalidDomainException->getMessage()
            );
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
        }

        $this->redirect('doctors/register', true);
    }

    public function registerForm(): View {
        $userId = $this->auth(
            $this->sessionManager,
            true,
            0,
            array(User::USER_ROLE_DOCTOR)
        );

        $doctor = null;

        try {
            $doctor = $this->doctorRepository->findByUserId($userId);
        } catch (DomainNotFoundException $domainNotFoundException) {

        }

        return new PHPTemplateView('register_doctor.php', array(
            'action-form' => 'doctor/register',
            'current_doctor' => $doctor,
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'message-success' => $this->sessionManager->getFlash('message-success')
        ));
    }

    public function update() {
        $userId = $this->auth(
            $this->sessionManager,
            true,
            0,
            array(User::USER_ROLE_DOCTOR)
        );

        $doctor = null;

        try {
            $oldDoctor = $this->doctorRepository->findByUserId($userId);

            $doctor = Doctor::fromArray($_POST);
            $doctor->validate();

            $doctor = $this->doctorRepository->registerDoctor($oldDoctor->getId(), $doctor);

            $this->sessionManager->setFlash('message-success', 'Doctor info registered');
        } catch (BadRequestException $badRequestException) {
            $this->sessionManager->setFlash(
                'message-danger',
                $badRequestException->getMessage()
            );
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash(
                'message-danger',
                $invalidDomainException->getMessage()
            );
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash(
                'message-danger',
                $invalidDomainException->getMessage()
            );

            $this->redirect('panel', true);
        } catch (\Exception $ex) {
            error_log($ex->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
        }

        $this->redirect('doctors/register', true);
    }

}
