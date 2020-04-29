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
     * @throws Exception
     */
    public function getTarifs(int $userId, int $serviceId)
    {
        $responseData = [];

        $queryResult = (new ServiceModel())->getUserServiceTarifGroup($userId, $serviceId);
        if (!count($queryResult)) {
            $content = json_encode(['result' => 'not_found']);
            Response::send($content, Response::TYPE_JSON, Response::STATUS_OK);
            return;
        }

        $responseData['result'] = 'ok';

        $tariffs = [];

        foreach ($queryResult as $item) {
            $tariffs['title'] = TariffModel::GROUPS[$item['tarif_group_id']];
            unset($item['tarif_group_id']);
            $tariffs['link'] = $item['link'];
            unset($item['link']);
            $tariffs['speed'] = $item['speed'];
            $currentDate = (new DateTime())->format('Y-m-d') . ' 00:00:00';
            $item['new_payday'] = (new DateTime($currentDate . '+' . $item['pay_period'] . ' months'))->format('UO');
            $tariffs['tariffs'][] = $item;
        }

        $responseData['tariffs'] = $tariffs;

        $content = json_encode($responseData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        Response::send($content, Response::TYPE_JSON, Response::STATUS_OK);
    }
}