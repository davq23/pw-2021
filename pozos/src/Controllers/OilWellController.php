<?php

namespace Controllers;

use App\SessionManager;
use Domains\Exceptions\InvalidDomainException;
use Domains\Measurement;
use Domains\OilWell;
use Repositories\Exceptions\DomainNotFoundException;
use Repositories\OilWellRepository;
use Repositories\UserRepository;
use Throwable;
use Views\JSONDataView;
use Views\JSONDomainView;
use Views\JSONMessageView;
use Views\PHPTemplateView;
use Views\View;

/**
 * Description of OilWellController
 *
 * @author davido
 */
class OilWellController extends Controller
{
    private OilWellRepository $oilWellRepository;
    private SessionManager $sessionManager;
    private UserRepository $userRepository;

    public function __construct(
        OilWellRepository $oilWellRepository,
        SessionManager $sessionManager,
        UserRepository $userRepository
    ) {
        $this->oilWellRepository = $oilWellRepository;
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
    }

    public function registerForm(): View {
        $userId = $this->auth($this->sessionManager);

        $current_user = $this->userRepository->findById($userId);

        return new PHPTemplateView('new-well.php', array(
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'oil_well' => $this->sessionManager->getFlash('oil_well'),
            'current_user' => $current_user
        ));
    }

    public function updateForm(): View {
        $userId = $this->auth($this->sessionManager);

        $oilWellId = filter_input(INPUT_GET, 'oil_well');

        $current_user = $this->userRepository->findById($userId);

        try {
            $oilWell = $this->oilWellRepository->findById($oilWellId);
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Invalid oil well');
            $this->redirect('panel');
        }


        return new PHPTemplateView('update-well.php', array(
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'oil_well' => $oilWell,
            'current_user' => $current_user
        ));
    }

    public function register(): void {
        $this->auth($this->sessionManager);

        $error = false;

        $oilWellArray = filter_input_array(INPUT_POST, array(
            'name' => FILTER_DEFAULT,
            'depth' => FILTER_VALIDATE_FLOAT,
            'estimated_reserves' => FILTER_VALIDATE_FLOAT
            ), true);

        if (!$oilWellArray) {
            $this->sessionManager->setFlash('message-danger', 'Invalid oil well info');
            $this->redirect('well/new', true);
        }

        try {
            $oilWell = OilWell::fromArray($oilWellArray);
            $oilWell->validate();

            $this->oilWellRepository->registerOilWell($oilWell);

            $this->sessionManager->setFlash(
                'message-success',
                $oilWell->getName() . ' successfully registered'
            );
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $this->sessionManager->setFlash('oil_well', $oilWellArray);

            $error = true;
        } catch (\Exception $exception) {
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
            $this->sessionManager->setFlash('oil_well', $oilWellArray);

            $error = true;
        }

        $this->redirect($error ? 'well/new' : 'panel', true);
    }

    public function update(): void {
        $this->auth($this->sessionManager);

        $oilWellArray = filter_input_array(INPUT_POST, array(
            'id' => FILTER_DEFAULT,
            'name' => FILTER_DEFAULT,
            'depth' => FILTER_VALIDATE_FLOAT,
            'estimated_reserves' => FILTER_VALIDATE_FLOAT
        ));

        if (!$oilWellArray) {
            $this->sessionManager->setFlash('message-danger', 'Invalid oil well info');
            $this->redirect('panel', true);
        }

        try {
            $oldOilWell = $this->oilWellRepository->findById($oilWellArray['id']);
            $oilWell = OilWell::fromArray($oilWellArray);
            $oilWell->validate();

            $this->oilWellRepository->updateOilWell($oilWell);

            $this->sessionManager->setFlash(
                'message-success',
                $oilWell->getName() . ' successfully updated'
            );
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Unknown oil well id');
        } catch (\Exception $exception) {
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
        }

        $this->redirect('panel', true);
    }

    public function delete(): void {
        $this->auth($this->sessionManager);

        $oilWellArray = filter_input_array(INPUT_POST, array(
            'id' => FILTER_DEFAULT
            )
        );

        if (!$oilWellArray) {
            $this->sessionManager->setFlash('message-danger', 'Invalid oil well info');
            $this->redirect('panel', true);
        }

        try {
            $oldOilWell = $this->oilWellRepository->findById($oilWellArray['id']);

            $this->oilWellRepository->deleteOilWell($oldOilWell->getId());

            $this->sessionManager->setFlash(
                'message-success',
                'Deleted well "' . $oldOilWell->getName() . '"'
            );
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Unknown oil well id');
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
        }

        $this->redirect('panel', true);
    }

    public function addMeasurement(): View {
        $userId = $this->auth($this->sessionManager, true, self::AJAX_REQUEST);

        $measurementArray = $this->getJsonBody();

        if (!$measurementArray) {
            return new JSONMessageView(400, 'Bad request');
        }

        $measurementArray['user_id'] = $userId;

        $measurementArray['time'] = sprintf(
            '%s %s',
            $measurementArray['date'],
            $measurementArray['time']
        );

        try {
            $oilWell = $this->oilWellRepository->findById($measurementArray['oil_well_id'] ?? null);

            $measurement = Measurement::fromArray($measurementArray);
            $measurement->validate();

            try {
                $this->oilWellRepository
                    ->findMeasurementByTime($oilWell, $measurement->getTime());

                throw new InvalidDomainException('Measurement for oil well already done at this time');
            } catch (DomainNotFoundException $domainNotFoundException) {

            }

            $this->oilWellRepository->addMeasurement($oilWell, $measurement);
        } catch (InvalidDomainException $invalidDomainException) {
            return new JSONMessageView(422, $invalidDomainException->getMessage());
        } catch (DomainNotFoundException $domainNotFoundException) {
            return new JSONMessageView(404, 'Invalid oil well id');
        } catch (Throwable $th) {
            error_log($th);
            return new JSONMessageView(500, 'Unknown error');
        }

        return new JSONDomainView('Measurement successfully added', $measurement);
    }

    public function deleteMeasurement(): void {
        $this->auth($this->sessionManager);

        $error = false;

        $measurementArray = filter_input_array(INPUT_POST, array(
            'id' => FILTER_DEFAULT
        ));

        if (!$measurementArray) {
            $this->sessionManager->setFlash('message-danger', 'Invalid measurement');
            $this->redirect('measurements/edit', true);
        }

        try {
            $oldMeasurement = $this->oilWellRepository->findMeasurementById($measurementArray['id'] ?? null);

            $this->oilWellRepository->deleteMeasurement($oldMeasurement->getId());
            $this->sessionManager->setFlash(
                'message-success',
                'Measurement to successfully deleted'
            );
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $error = true;
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Invalid measurement id');
            $error = true;
        } catch (Throwable $th) {
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
            $error = true;
        }

        $this->redirect($error ? 'measurements/edit' : 'panel', true);
    }

    public function editMeasurement(): void {
        $this->auth($this->sessionManager);

        $error = false;

        $measurementArray = filter_input_array(INPUT_POST, array(
            'id' => FILTER_DEFAULT,
            'value' => FILTER_VALIDATE_FLOAT,
            'time' => FILTER_DEFAULT,
            'date' => FILTER_DEFAULT
        ));

        if (!$measurementArray) {
            $this->sessionManager->setFlash('message-danger', 'Invalid measurement');
            $this->redirect('measurements/edit', true);
        }

        $measurementArray['time'] = sprintf(
            '%s %s',
            $measurementArray['date'],
            $measurementArray['time']
        );

        try {
            $oldMeasurement = $this->oilWellRepository->findMeasurementById($measurementArray['id'] ?? null);
            $measurement = Measurement::fromArray($measurementArray);
            $measurement->validate();
            $measurement->setOilWellId($oldMeasurement->getOilWellId());

            $this->oilWellRepository->editMeasurement($measurement);
            $this->sessionManager->setFlash(
                'message-success',
                'Measurement to successfully editted'
            );
        } catch (InvalidDomainException $invalidDomainException) {
            $this->sessionManager->setFlash('message-danger', $invalidDomainException->getMessage());
            $error = true;
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Invalid measurement id');
            $error = true;
        } catch (Throwable $th) {
            $this->sessionManager->setFlash('message-danger', 'Unknown error');
            $error = true;
        }

        $this->redirect($error ? 'measurements/edit' : 'panel', true);
    }

    public function editMeasurementForm(): View {
        $userId = $this->auth($this->sessionManager);

        $measurementId = filter_input(INPUT_GET, 'measurement');

        $current_user = $this->userRepository->findById($userId);

        try {
            $measurement = $this->oilWellRepository->findMeasurementById($measurementId);
            $oilWell = $this->oilWellRepository->findById($measurement->getOilWellId());
        } catch (DomainNotFoundException $domainNotFoundException) {
            $this->sessionManager->setFlash('message-danger', 'Invalid measurement id');
            $this->redirect('panel');
        }


        return new PHPTemplateView('update-measurement.php', array(
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'measurement' => $measurement,
            'oil_well' => $oilWell,
            'current_user' => $current_user
        ));
    }

    public function getMeasurements(): View {
        $userId = $this->auth($this->sessionManager, true, self::AJAX_REQUEST);

        $measurementArray = filter_input_array(INPUT_GET, array(
            'oil_well' => FILTER_DEFAULT,
            'year' => FILTER_VALIDATE_INT,
            'month' => FILTER_VALIDATE_INT,
            'day' => FILTER_VALIDATE_INT
            ), true);

        if (!$measurementArray)
            return new JSONMessageView(400, 'Bad request');

        try {
            $oilWell = $this->oilWellRepository->findById($measurementArray['oil_well']);

            $measurements = $this->oilWellRepository->findAllMeasurements(
                $oilWell,
                $measurementArray['year'],
                $measurementArray['month'],
                $measurementArray['day']
            );
        } catch (DomainNotFoundException $domainNotFoundException) {
            return new JSONMessageView(404, 'Oil well not found');
        } catch (Throwable $th) {
            error_log($th);
            return new JSONMessageView(500, 'Unknown error');
        }

        return new JSONDataView($measurements);
    }

}
