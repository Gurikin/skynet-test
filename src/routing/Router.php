<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 10:38
 */


class Router
{
    /** @var Router */
    private static $instance;
    /** @var string */
    private $controller;
    /** @var string */
    private $action;
    /** @var array */
    private $parameters;
    /** @var string */
    private $method;

    /**
     * Router constructor.
     */
    private function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $urlParameters = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->controller = !empty($urlParameters[0]) ? ucfirst($urlParameters[0]) . 'Controller' : 'NotFoundController';
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->action = mb_strtolower($this->method);
        for ($i = 0, $iMax = count($urlParameters); $i < $iMax; $i++) {
            if ($i % 2 === 0) {
                $this->action .= ucfirst($urlParameters[$i]);
            } else {
                $this->parameters[] = $urlParameters[$i];
            }
        }
        if (in_array($this->method, ['POST', 'PUT'])) {
            $content = json_decode(file_get_contents('php://input'), true);
            $this->parameters[] = $content;
        }
    }

    private function __clone() { }

    /**
     * @return Router
     */
    public static function getInstance(): \Router
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function route(): void
    {
        if (!class_exists($this->getController())) {
            header('Location: 404.php');
        }

        $rc = new ReflectionClass($this->getController());

        if (!$rc->implementsInterface('IController')) {
            header('Location: 404.php');
        }

        if (!$rc->hasMethod($this->getAction())) {
            header('Location: 404.php');
        }

        $controller = $rc->newInstance();
        $method = $rc->getMethod($this->getAction());

        if (empty($this->getParameters())) {
            $method->invoke($controller);
            return;
        }
        
        $method->invokeArgs($controller, $this->getParameters());
    }

    /**
     * @return string
     */
    private function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    private function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    private function getParameters(): array
    {
        return $this->parameters;
    }
}
