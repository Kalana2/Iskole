<?php
class App
{
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if ($url && isset($url[0]) && $url[0] !== '' && file_exists(__DIR__ . '/../Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        $public = ['LoginController'];
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
