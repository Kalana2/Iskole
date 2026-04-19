<?php
class App
{
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        $controllerPath = null;

        if ($url && isset($url[0]) && $url[0] !== '') {
            $segment0 = $url[0];

            $rootControllerFile = __DIR__ . '/../Controllers/' . ucfirst($segment0) . 'Controller.php';
            $folderControllerFile = __DIR__ . '/../Controllers/' . $segment0 . '/' . ucfirst($segment0) . 'Controller.php';

            if (file_exists($rootControllerFile)) {
                $this->controller = ucfirst($segment0) . 'Controller';
                $controllerPath = $rootControllerFile;
                unset($url[0]);
            } elseif (file_exists($folderControllerFile)) {
                $this->controller = ucfirst($segment0) . 'Controller';
                $controllerPath = $folderControllerFile;
                unset($url[0]);
            } else {
                header('Location: /login');
                exit;
            }
        } else {
            header('Location: /login');
            exit;
        }

        require_once $controllerPath;
        $this->controller = new $this->controller;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        $public = ['LoginController', 'AcademicOverviewController'];
        if (!in_array($this->controller::class, $public) && !isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
