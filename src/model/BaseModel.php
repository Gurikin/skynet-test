<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 23:51
 */


abstract class BaseModel
{
    /** @var PDO */
    protected $connection;

    /**
     * BaseModel constructor.
     */
    public function __construct()
    {
        $this->connection = DBConnection::getInstance()->getConnection();
    }

    /**
     * @param array $tariff
     * @return BaseModel
     */
    public function getFromArray(array $tariff)
    {
        foreach ($tariff as $fieldName => $fieldValue) {
            if (property_exists(get_class($this), $fieldName)) {
                $this->$fieldName = $fieldValue;
            }
        }
        return $this;
    }
}
