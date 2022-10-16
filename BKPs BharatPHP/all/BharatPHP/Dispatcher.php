<?php

namespace BharatPHP;

use BharatPHP\Application;

class Dispatcher
{

    public function dispatch(Application $app)
    {
        $params = $app->router()->getParams();
        $controller = $params['controller'];
        $controller = $this->convertToStudlyCaps($controller);
        $controller = $this->getNamespace($params) . $controller;

        if (class_exists($controller)) {

            $controller_obj = new $controller($app);
            if (!isset($params['action'])) {
                $params['action'] = 'index';
            }

            $controller_obj->setParams($params);
            $action = $params['action']; //get the action to call
            $action = $this->convertToCamelCase($action);

            if (is_callable(array($controller_obj, $action))) {
                $app->events()->trigger('before.app.controller.method', array(
                    'app' => $app,
                ));

                // print_r([$action,$controller_obj]);
                // die('xx2x');

                $controller_obj->$action();
                $app->events()->trigger('after.app.controller.method', array('app' => $app));
            } else {

                throw new \Exception("Method $action (in controller $controller) not found");
            }
        } else {

            throw new \Exception("Controller class $controller not found");
        }
    }

    private function convertToStudlyCaps($str)
    {
        return str_replace(' ', '', ucfirst(str_replace('-', ' ', $str)));
    }

    private function convertToCamelCase($str)
    {
        return lcfirst($this->convertToStudlyCaps($str));
    }

    private function getNamespace($params)
    {
        return '' . ((array_key_exists('namespace', $params)) ? $params['namespace'] . '\\' : '');
    }
}
