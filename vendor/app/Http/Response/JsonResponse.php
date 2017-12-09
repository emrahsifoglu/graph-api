<?php

namespace App\Vendor\Http\Response;

class JsonResponse extends Response
{

    public function __construct(
        $data = null,
        $status = 200,
        $encodingOptions = 15
    ) {
        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json', true, $status);
        echo json_encode($data, $encodingOptions);
        flush();
        ob_flush();
        exit;
    }

}