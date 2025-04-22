<?php

abstract class Controller
{
    public function render($view, $viewBag = [])
    {
        $file = __DIR__ . "/../views/" . static::class . "/$view";
        $file = str_replace("Controller", "", $file);
        if (is_file($file)) {
            extract($viewBag);
            ob_start(); //Abre el buffer
            require($file);
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        } else {
            echo "<h1> View not found</h1>";
        }
    }
}
