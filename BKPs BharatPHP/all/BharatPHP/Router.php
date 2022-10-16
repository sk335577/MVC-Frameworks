<?php

namespace BharatPHP;

use BharatPHP\Exception\NotFoundException;

class Router
{
    private Request $request;
    private Response $response;
    private static array $routeMap = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public static function get(string $url, $callback)
    {
        self::$routeMap['get'][$url] = $callback;
    }

    public static function post(string $url, $callback)
    {
        self::$routeMap['post'][$url] = $callback;
    }

    /**
     * @return array
     */
    public function getRouteMap($method): array
    {
        return self::$routeMap[$method] ?? [];
    }

    public function getCallback()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        // Trim slashes
        $url = trim($url, '/');

        // Get all routes for current request method
        $routes = $this->getRouteMap($method);

        $routeParams = false;

        // Start iterating registed routes
        foreach ($routes as $route => $callback) {
            // Trim slashes
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            // Find all route names from route and save in $routeNames
            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            // Convert route name into regex pattern
            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn ($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

            // Test and match current route against $routeRegex
            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);

                $this->request->setRouteParams($routeParams);
                return $callback;
            }
        }

        return false;
    }

    public function resolve()
    {

        $method = $this->request->getMethod();
        $url = $this->request->getUrl();


        $callback = self::$routeMap[$method][$url] ?? false;


        if (!$callback) {
            $callback = $this->getCallback();
            if ($callback === false) {
                throw new NotFoundException();
            }
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }


        if (is_array($callback)) {

            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::app()->controller = $controller;
            $middlewares = $controller->getMiddlewares();
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        // return call_user_func($callback, $this->request, $this->response);
        return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = [])
    {
        return Application::app()->view()->renderView($view, $params);
    }

    public function renderViewOnly($view, $params = [])
    {
        return Application::app()->view()->renderViewOnly($view, $params);
    }
}
/*
class Router
{

    protected $routes;
    protected $params;

    public function addRoutes($routes)
    {
        foreach ($routes as $route) {
            $route_pattern = $route[0]; //dynamic route
            $route_params = array();
            if (isset($route[1])) {
                $route_params = $route[1]; //route params
            }

            // Convert the route to a regular expression: escape forward slashes
            $route_pattern = preg_replace('/\//', '\\/', $route_pattern);

            // Convert variables e.g. {controller}
            $route_pattern = preg_replace('/\{([a-z]+)\}/', "(?<$1>[a-z-]+)", $route_pattern);

            // Convert variables with custom regular expressions e.g. {id:\d+}
            $route_pattern = preg_replace('/\{([a-z]+):([^\}]+)\}/', "(?<$1>$2)", $route_pattern);

            // Add start and end delimiters, and case insensitive flag
            $route_pattern = '/^' . $route_pattern . '$/i';
            $this->routes[$route_pattern] = $route_params;
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function match($url)
    {

        $url = $this->filterUrl($url);
        $route_params = array();


        foreach ($this->routes as $route => $params) {

            if (preg_match($route, $url, $matches) === 1) {
                $route_params = array();
                foreach ($params as $key => $value) {
                    $route_params[$key] = $value;
                }
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $route_params[$key] = $match;
                    }
                }
                break;
            }
        }

        if (!empty($route_params)) {
            $this->params = $route_params;
            return true;
        }

        return false;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function route($url)
    {
        $url = $this->removeQueryStringVariables($url);
        if ($this->match($url)) {
            return true;
        } else {
            return false;
        }
    }


    protected function removeQueryStringVariables($url)
    {

        if (!empty($url)) {
            // $parts = explode('&', $url, 2);
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }


    protected function filterUrl($url)
    {
        if (preg_match('/(?<url>.+)\/$/', $url, $matches) === 1) {
            $url = $matches['url'];
        }
        $url = trim($url);
        return $url;
    }
}
*/