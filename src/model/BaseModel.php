<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 23:51
 */


abstract class BaseModel
{
    /** @var PDO */
    protected $connection;

    public function __construct()
    {
        $this->connection = DBConnection::getInstance()->getConnection();
    }
}
