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

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->parameters = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->controller = !empty($this->parameters[0]) ? ucfirst($this->parameters[0]) . 'Controller' : 'NotFoundController';
        $this->action = mb_strtolower($_SERVER['REQUEST_METHOD']);
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
        if (class_exists($this->getController())) {
            $rc = new ReflectionClass($this->getController());
            if ($rc->implementsInterface('IController')) {
                if ($rc->hasMethod($this->getAction())) {
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    if (!empty($this->_params)) {
                        $method->invokeArgs($controller, $this->_params);
                    } else {
                        $method->invoke($controller);
                    }
                } else {
                    //TODO add the 404 redirection
                    throw new \RuntimeException('Action not found');
                }
            } else {
                //TODO add the 404 redirection
                throw new \RuntimeException('Controller must implement IController interface');
            }
        } else {
            //TODO add the 404 redirection
            throw new \RuntimeException('Controller not found');
        }
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
