<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.2
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Redirect to a route
     *
     * @param string $url  The route
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function redirect($url, $args=[])
    {
        $argGet = (isset($args['id'])) ? "?arg={$args['id']}" : "";
        try {
            header("Location: {$url}{$argGet}", true, 302);
            exit();
        } catch (\exception $e) {
            throw new \Exception("{$e}: {$url} not found");
        }
    }
}
