<?php

namespace App\Vendor\View;

class Viewer
{

    public function renderView($view, $params = []) {
        $view = new View($view, $params);
        $content = $view->getContent();

        ob_start();
        header( 'Content-type: text/html; charset=utf-8');
        extract($params);
        eval(' ?>' . $content);
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }

    public function render($view, $params = []) {
        $this->output($this->renderView($view, $params));
    }

    public function output($content) {
        ob_start();
        echo $content;
        ob_end_flush();
    }

}
