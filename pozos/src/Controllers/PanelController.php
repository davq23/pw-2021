<?php

namespace Controllers;

use App\SessionManager;
use Repositories\OilWellRepository;
use Repositories\UserRepository;
use Views\PHPTemplateView;

/**
 * Description of PanelController
 *
 * @author davido
 */
class PanelController extends Controller
{
    private SessionManager $sessionManager;
    private UserRepository $userRepository;
    private OilWellRepository $oilWellRepository;

    public function __construct(
        SessionManager $sessionManager,
        UserRepository $userRepository,
        OilWellRepository $oilWellRepository
    ) {
        $this->sessionManager = $sessionManager;
        $this->userRepository = $userRepository;
        $this->oilWellRepository = $oilWellRepository;
    }

    public function index(): PHPTemplateView {
        $userId = $this->auth($this->sessionManager);

        $currentUser = $this->userRepository->findById($userId);
        $oilWells = $this->oilWellRepository->findAllOilWells();

        return new PHPTemplateView('panel.php', array(
            'current_user' => $currentUser,
            'oil_wells' => $oilWells,
            'message-success' => $this->sessionManager->getFlash('message-success'),
            'message-danger' => $this->sessionManager->getFlash('message-danger'),
        ));
    }

}
