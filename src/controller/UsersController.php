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
        $data = (new ServiceModel())->getUserServiceTarifGroup($userId, $serviceId);
        var_dump($data);
    }
}