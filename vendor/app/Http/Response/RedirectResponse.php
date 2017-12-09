<?php

namespace App\Vendor\Http\Response;

class RedirectResponse extends Response
{

    public function __construct(
        $location
    ) {
        header('Content-type: text/html; charset=utf-8' );
        header("Location: " . $location, true );
        echo "<html></html>";
        flush();
        ob_flush();
        exit;
    }

}
