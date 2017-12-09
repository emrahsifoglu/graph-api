<?php

namespace App\Vendor\Controller;

use App\Vendor\Http\Session\Session;
use App\Vendor\View\Viewer;
use stdClass;

class Controller
{

    protected $viewer;
    protected $session;

    public function __construct(
        Viewer $viewer,
        Session $session
    ) {
        $this->viewer = $viewer;
        $this->session = $session;
    }

    public function renderView($view, $params = []) {
        return $this->viewer->renderView($view, $params);
    }

    public function render($view, $params = []) {
        $app = new stdClass();
        $app->is_granted = $this->session->get('is_granted');
        $app->user = json_decode($this->session->get('user'));

        $params['app'] = $app;

        $this->viewer->output($this->renderView($view, $params));
    }

}
