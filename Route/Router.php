<?php
class Router
{

    static function currentUrl()
    {


        return  $_SERVER['REQUEST_URI'];
    }

    // indexController@index
    static function start($url,$callback)
    {
        $url = preg_replace('/\{(.*?)\}/','(.*)',$url);
        if(preg_match('@^'.$url.'$@',self::currentUrl(),$parameters)) {
            unset($parameters[0]);
            if (is_callable($callback)) {
                call_user_func_array($callback, $parameters);
            }
            else
            {
                $currentController = explode('@', $callback);

                if (file_exists('App/' . $currentController[0] . '.php')) {
                    require_once 'App/' . $currentController[0] . '.php';
                    call_user_func_array([new $currentController[0], $currentController[1]], $parameters);
                } else {
                    die("404 - Controller is not Found");
                }
            }
        }

    }



}

?>