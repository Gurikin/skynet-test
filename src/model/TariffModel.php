<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 30.04.2020 0:16
 */


class TariffModel extends BaseModel
{
    public const GROUPS = [
        1 => 'Земля',
        2 => 'Вода'
    ];

    /**
     * @param int $tariffId
     * @param array $variant
     * @return array
     */
    public function getOneById(int $tariffId, array $variant = ['*'])
    {
        $select = implode(',', $variant);
        $query = 'select ' . $select . ' from tarifs where id = ' . $tariffId;
        $queryResult = $this->connection->query($query);
        return $queryResult !== false ? $queryResult->fetch(PDO::FETCH_ASSOC) : [];
    }
}
