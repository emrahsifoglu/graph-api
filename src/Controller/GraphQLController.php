<?php

namespace App\Controller;

use App\Vendor\Config\ConfigInterface;
use App\Vendor\Controller\Controller;
use App\Vendor\Http\Response\JsonResponse;
use App\Vendor\Http\Session\Session;
use App\Vendor\View\Viewer;
use App\Vendor\Http\Request\Request;
use App\GraphQL\Schema\TickSchema;
use App\Entity\Tick\TickFacade;
use Youshido\GraphQL\Execution\Processor;

class GraphQLController extends Controller
{

    /** @var TickFacade */
    private $tickFacade;

    /** @var ConfigInterface */
    private $configInterface;

    /**
     * GraphQLController constructor.
     * @param Viewer $viewer
     * @param Session $session
     * @param TickFacade $tickFacade
     * @param ConfigInterface $configInterface
     */
    public function __construct(
        Viewer $viewer,
        Session $session,
        TickFacade $tickFacade,
        ConfigInterface $configInterface
    ) {
        parent::__construct($viewer, $session);
        $this->tickFacade = $tickFacade;
        $this->configInterface = $configInterface;
    }

    /**
     * @param Request $request
     * @return JsonResponse|null
     */
    public function indexAction(Request $request) {
        if ($request->getMethod() == "OPTIONS") {
            return null;
        }

        $contentType = $request->getContentType();
        $httpContentType = $request->getHttpContentType();

        if ((isset($contentType) && $contentType === 'application/json')
            || isset($httpContentType) && $httpContentType === 'application/json'
        ) {
            $rawBody     = file_get_contents('php://input');
            $requestData = json_decode($rawBody ?: '', true);
        } else {
            $requestData = $_POST;
        }

        $payload   = isset($requestData['query']) ? $requestData['query'] : null;
        $variables = isset($requestData['variables']) ? $requestData['variables'] : null;

        $processor = new Processor(new TickSchema($this->tickFacade));
        $response = $processor->processPayload($payload, $variables)->getResponseData();

        return new JsonResponse($response);
    }

    public function explorerAction() {
        $endpoint =  $this->configInterface->getConfig()['graphQL']['endpoint'];
        $this->render('GraphQL:explorer', [
            'graphQLUrl' => $endpoint
        ]);
    }
}
