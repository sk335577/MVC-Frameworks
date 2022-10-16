<?php

namespace BharatPHP;

use BharatPHP\Router;
use BharatPHP\Services;
use BharatPHP\Events;
use BharatPHP\Dispatcher;
use BharatPHP\Request;
use BharatPHP\Response;
use BharatPHP\Config;

class Application
{

    private static Application $app;

    public Router $router;
    public Services $services;
    public Request $request;
    public Response $response;
    public Events $events;
    public View $view;
    public Dispatcher $dispatcher; //calls the controller and its function
    public $controller = null;

    public function __construct($config = array())
    {

        self::$app = $this;

        Config::init($config); //Save config in class

        $this->request = new Request();

        $this->response = new Response();

        $this->view = new View();

        $this->registerServices(new Services());


        // $this->services()->set('config', [
        //     'call' => '\BharatPHP\Config::init',
        //     'params' => [
        //         'config' => $config,

        //     ]
        // ]);

        // $this->services()->set('translator', [
        //     'call' => '\BharatPHP\Translator::init',
        //     'params' => [
        //         'config' => $config,

        //     ]
        // ]);

        $this->registerRouter(new Router($this->request, $this->response));

        // $this->registerDispatcher(new Dispatcher());

        $this->registerEvents(new Events());

        // /* Load routes */
        // if (isset($config['routes'])) {
        //     $this->router->addRoutes($config['routes']);
        // }

        // /* Load services */
        if (isset($config['services'])) {
            foreach ($config['services'] as $name => $service) {
                $this->services->set($name, $service);
            }
        }

        // /* Load events */
        if (isset($config['events'])) {
            foreach ($config['events'] as $event) {
                if (isset($event['name']) && isset($event['action'])) {
                    $this->events->on($event['name'], $event['action'], ((isset($event['priority'])) ? $event['priority'] : 0));
                }
            }
        }
    }

    public static function app(): Application
    {
        return self::$app;
    }


    public function registerRequest(Request $request)
    {
        $this->request = $request;
    }

    public function registerRouter(Router $router)
    {
        $this->router = $router;
    }

    public function registerServices(Services $services)
    {
        $this->services = $services;
    }

    public function registerEvents(Events $events)
    {
        $this->events = $events;
    }

    public function registerDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function configs()
    {
        return $this->config;
    }

    public function services()
    {
        return $this->services;
    }

    public function router()
    {
        return $this->router;
    }
    public function request()
    {
        return $this->request;
    }
    public function view()
    {
        return $this->view;
    }

    public function events()
    {
        return $this->events;
    }


    public function run()
    {
        $this->events->trigger('on.app.run', array('app' => $this));
        $this->events->trigger('before.app.route', array('app' => $this));
        // $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            $this->router->resolve();
        } catch (\Exception $e) {

            echo $this->router->renderView('errors/404', [
                'exception' => $e,
            ]);
        }
    }

    // public function run()
    // {

    //     date_default_timezone_set(Config::get('timezone'));

    //     $this->events->trigger('on.app.run', array('app' => $this));

    //     $this->events->trigger('before.app.route', array('app' => $this));


    //     if ($this->router->route($_SERVER['REQUEST_URI'])) {

    //         // Translator::load($this->services()->get('config'), $this->router());

    //         $this->events->trigger('before.app.dispatch', array('app' => $this));
    //         $this->dispatcher->dispatch($this);
    //         $this->events->trigger('after.app.dispatch', array('app' => $this));
    //     } else {
    //         //not route found
    //         $this->events->trigger('on.app.route.not_found', array('app' => $this));
    //     }

    //     $this->events->trigger('after.app.route', array('app' => $this));
    // }
}
