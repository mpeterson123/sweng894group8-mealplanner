<?php
namespace Base\Helpers;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Redirects from one controller to another
 */
class Redirect{

    /**
     * Redirects to another controller method
     * @param  string $controllerName Destination controller's namespace
     * @param  string $methodName     Destination method's namespace
     * @param  array  $params         Parameters to pass to method
     * @return void
     */
    public static function toControllerMethod($controllerName, $methodName, $params = NULL){
        $queryStringParams = self::queryStringifyParams($params);
        header('Location: /'.$controllerName.'/'.$methodName.'/'.$queryStringParams);
        exit();
    }

    /**
     * Convert parameters to URL-friendly format
     * @param  array $params    URL parameters after controller method
     * @return string           URL-friendly string with parameters
     */
    private static function queryStringifyParams($params = NULL){
        if(!$params){
            return '';
        }
        return implode('/', $params);
    }

}
