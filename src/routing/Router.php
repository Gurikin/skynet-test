<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 10:38
 */


class Router
{
    private static $_instance;

    private function __construct()
    {
    }

    private function __clone(){}

    /**
     * @return object $_instance - the instance of the FrontController
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}