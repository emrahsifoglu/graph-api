<?php

namespace App\Controller;

use App\Entity\Tick\TickFacade;
use App\Vendor\Controller\Controller;
use App\Vendor\Http\Response\JsonResponse;
use App\Vendor\Http\Session\Session;
use App\Vendor\View\Viewer;

class TickController extends Controller
{
    /** @var TickFacade */
    private $tickFacade;

    /**
     * TickController constructor.
     * @param Viewer $viewer
     * @param Session $session
     * @param TickFacade $tickFacade
     */
    public function __construct(
        Viewer $viewer,
        Session $session,
        TickFacade $tickFacade
    ) {
        parent::__construct($viewer, $session);
        $this->tickFacade = $tickFacade;
    }

    public function indexAction() {
        $ticks = $this->tickFacade->getAllTicks();
        $data = $this->tickFacade->generateGraphData($ticks);

        return new JsonResponse($data);
    }

}
