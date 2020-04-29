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
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $urlParameters = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->controller = !empty($urlParameters[0]) ? ucfirst($urlParameters[0]) . 'Controller' : 'NotFoundController';
        $this->method = $_SERVER['HTTP_X_HTTP_METHOD'];
        $this->action = mb_strtolower($this->method);
        die($this->action . ' __ ' . $this->method . ' __ ' . $_SERVER['REQUEST_METHOD']);
        for ($i = 0, $iMax = count($urlParameters); $i < $iMax; $i++) {
            if ($i % 2 === 0) {
                $this->action .= ucfirst($urlParameters[$i]);
            } else {
                $this->parameters[] = $urlParameters[$i];
            }
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
        var_dump(self::$instance);
        if (class_exists($this->getController())) {
            $rc = new ReflectionClass($this->getController());
            if ($rc->implementsInterface('IController')) {
                if ($rc->hasMethod($this->getAction())) {
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    if (!empty($this->getParameters())) {
                        $method->invokeArgs($controller, $this->getParameters());
                    } else {
                        $method->invoke($controller);
                    }
                } else {
                    throw new \RuntimeException('Action not found');
                }
            } else {
                throw new \RuntimeException('Controller must implement IController interface');
            }
        } else {
            throw new \RuntimeException('Controller not found');
        }
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
