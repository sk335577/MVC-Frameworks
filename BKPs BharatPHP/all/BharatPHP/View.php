<?php

namespace BharatPHP;

use BharatPHP\Request;
use BharatPHP\Config;

class View
{

    // protected $paths = array(); //all views paths default or frontend, backend, anything
    // protected $translator = null;
    protected $request = null;
    protected $viewtype = 'default';
    protected $layout = 'default';

    public string $page_title = '';

    // public function renderView($view, array $params)
    // public function renderView2($view, array $params, $layout = 'layouts/default')
    // {
    //     // $layoutName = Application::$app->layout;
    //     $layoutName = $layout;
    //     if (Application::$app->controller) {
    //         $layoutName = Application::$app->controller->layout;
    //     }
    //     $viewContent = $this->renderViewOnly($view, $params, $layout = 'default');
    //     ob_start();
    //     // include_once BharatPHP_VIEW_PATH . "/views/layouts/$layoutName.php";
    //     include_once BharatPHP_VIEW_PATH . "/$layoutName.phtml";
    //     $layoutContent = ob_get_clean();
    //     return str_replace('{{content}}', $viewContent, $layoutContent);
    // }

    public function renderViewOnly($view, array $params, $layout = 'layouts/default')
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        // include_once Application::$ROOT_DIR . "/views/$view.php";
        include_once BharatPHP_VIEW_PATH . "/$view.phtml";
        return ob_get_clean();
    }

    // public function __construct($paths = array())
    // {
    //     $this->paths = $paths;
    //     $this->request = new Request();
    // }

    // public function setTranslator($translator)
    // {
    //     $this->translator = $translator;
    // }

    // public function translate($string, $default = '')
    // {
    //     return $this->translator->translate($string, $default);
    // }

    // public function setData($data)
    // {
    //     foreach ($data as $data_key => $value) {
    //         $this->{$data_key} = $value;
    //     }
    // }

    public function renderView($view, array $params, $layout = 'layouts/default', $viewtype = 'default')
    {

        $layout = config('paths.views.' . $viewtype) . '/' . $layout . '.phtml';
        $viewContent = $this->renderViewOnly($view, $params, $layout);

        ob_start();
        // include_once BharatPHP_VIEW_PATH . "/views/layouts/$layoutName.php";
        include_once $layout;
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    // public function renderViewX($view, array $params, $layout = 'default', $viewtype = 'default')
    // {

    //     $layoutfile = config('paths.views.' . $viewtype) . '/layouts/' . $layout . '.phtml';

    //     // if (is_readable($layoutfile)) {
    //     require_once $layoutfile;
    //     // } else {
    //     // throw new \Exception("$layout not found");
    //     // }
    // }

    // public function renderTemplate($template = null, $layout = 'default', $viewtype = 'default')
    // {
    //     //        extract($data, EXTR_SKIP);
    //     $layoutfile = '';
    //     // if ($template == null) {
    //     //     $class = debug_backtrace()[1]['class'];
    //     //     $class = (explode('\\', $class));
    //     //     $class = strtolower(end($class));
    //     //     $fun = debug_backtrace()[1]['function'];
    //     //     $fun = str_replace('Action', '', $fun);
    //     //     $file = $this->paths['default'] . '/templates/' . $class . '/' . $fun . '.phtml';
    //     // } else {
    //     $this->template_data['template'] = $template;
    //     $this->template_data['layout'] = $layout;
    //     $this->template_data['viewtype'] = $viewtype;
    //     $layoutfile = $this->paths[$viewtype] . '/layouts/' . $layout . '.phtml';
    //     // }

    //     if (is_readable($layoutfile)) {
    //         require_once $layoutfile;
    //     } else {
    //         throw new \Exception("$layout not found");
    //     }
    // }

    // public function setType($viewtype = 'default')
    // {
    //     $this->viewtype = $viewtype;
    // }

    // public function setLayout($layout = 'default')
    // {
    //     $this->layout = $layout;
    // }

    // public function getType()
    // {
    //     return $this->viewtype;
    // }

    // public function getLayout()
    // {
    //     return $this->layout;
    // }

    public function getTemplatePart($part, $viewtype = 'default')
    {
        $template = config('paths.views.' . $viewtype) . '/' . $part . '.phtml';

        // ob_start();
        // include_once $template;
        // $content = ob_get_contents();
        // ob_end_clean();
        // return $content;
        ob_start();
        include_once $template;
        return ob_get_clean();
    }

    // public function getTemplate($template = '', $viewtype = 'default')
    // {
    //     ob_start();
    //     require_once $this->paths[$viewtype] . '/' . 'templates' . '/' . $template . '.phtml';
    //     $content = ob_get_contents();
    //     ob_end_clean();
    //     return $content;
    // }

    //    public function renderTemplate($template, $data = []) {
    //        static $twig = null;
    //        if ($twig === null) {
    //            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/modules/app/Views');
    //            $twig = new \Twig_Environment($loader);
    //        }
    //        echo $twig->render($template, $data);
    //    }
}
