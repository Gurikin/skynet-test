<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 21:35
 */


class UsersController extends AbstractController
{

    /**
     * @param int $userId
     * @param int $serviceId
     */
    public function getTarifs(int $userId, int $serviceId)
    {
        $data = DBConnection::getInstance()->getConnection()->query('select * from tarifs')->fetchAll(PDO::FETCH_ASSOC);
        var_dump($data);
    }
}