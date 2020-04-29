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
        $urlParameters = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->controller = !empty($urlParameters[0]) ? ucfirst($urlParameters[0]) . 'Controller' : 'NotFoundController';
        $lastKey = count($urlParameters) - 1;
        $this->action = mb_strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($urlParameters[$lastKey]);
        for ($i = 1; $i < count($urlParameters) - 1; $i += 2) {
            $this->parameters[] = $urlParameters[$i];
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
