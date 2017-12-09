<?php

namespace App\Controller;

use App\Vendor\Controller\Controller;
use App\Vendor\Http\Response\JsonResponse;
use App\GraphQL\Schema\AppSchema;
use App\Vendor\Http\Request\Request;
use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\Schema;

class GraphQLController extends Controller
{

    public function indexAction(Request $request) {
        if ($request->getMethod() == "OPTIONS") {
            return;
        }

        /** @var Schema $schema */
        $schema = new AppSchema();

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

        $processor = new Processor($schema);
        $response = $processor->processPayload($payload, $variables)->getResponseData();

        return new JsonResponse($response);
    }

    public function explorerAction() {
        $this->render('GraphQL:explorer', [
            'graphQLUrl' => 'http://192.168.222.128:8000/graphql'
        ]);
    }
}
