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
    public function getUsersServicesTarifs(int $userId, int $serviceId): void
    {
        $responseData = [];

        $queryResult = (new ServiceModel())->getUserServiceTarifGroup($userId, $serviceId);
        if (!count($queryResult)) {
            $content = json_encode(['result' => 'not_found']);
            Response::send($content, Response::TYPE_JSON, Response::STATUS_OK);
            return;
        }

        $responseData['result'] = 'ok';

        $responseData['tariffs'] = $this->wrapTariffs($queryResult);

        $content = json_encode($responseData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        Response::send($content, Response::TYPE_JSON, Response::STATUS_OK);
    }

    public function putUsersServicesTarif(int $userId, int $serviceId, array $body = [])
    {
        $responseData['result'] = 'ok';
        $responseData['data'] = [$userId, $serviceId, $body];

        $content = json_encode($responseData, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        Response::send($content, Response::TYPE_JSON, Response::STATUS_OK);
    }

    /**
     * @param array $inputData
     * @return array
     * @throws Exception
     */
    private function wrapTariffs(array $inputData): array
    {
        $tariffs = [];

        foreach ($inputData as $item) {
            $tariffs['title'] = TariffModel::GROUPS[$item['tarif_group_id']];
            unset($item['tarif_group_id']);
            $tariffs['link'] = $item['link'];
            unset($item['link']);
            $tariffs['speed'] = $item['speed'];
            $item['new_payday'] = DateTimeUtil::getDateFromCurrentMidNight($item['pay_period'])->format('UO');
            $tariffs['tariffs'][] = $item;
        }

        return $tariffs;
    }
}
