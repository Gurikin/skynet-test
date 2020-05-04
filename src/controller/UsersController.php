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

        $queryResult = (new ServiceModel())->getServiceTariffGroup($serviceId);
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

    /**
     * @param int $userId
     * @param int $serviceId
     * @param array $body
     * @throws Exception
     */
    public function putUsersServicesTarif(int $userId, int $serviceId, array $body = [])
    {
        $updateResult = (new ServiceModel())->setServiceTariff($serviceId, $body['tarif_id']);
        $responseData['result'] = $updateResult ? 'ok' : 'error';

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
            $tariffs['link'] = $item['link'];
            $tariffs['speed'] = $item['speed'];
            $item['new_payday'] = DateTimeUtil::getDateFromCurrentMidNight($item['pay_period'])->format(DateTimeUtil::TIMESTAMP_ZONE);
            $tariffs['tariffs'][] = (new TariffModel())->getFromArray($item);
        }

        return $tariffs;
    }
}
