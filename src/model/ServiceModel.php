<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 23:53
 */


class ServiceModel extends BaseModel
{
    /** @var string */
    private $tableName = 'services';

    /**
     * @param int $serviceId
     * @return array
     */
    public function getServiceTariffGroup(int $serviceId): array
    {
        $query = 'select t2.id, t2.title, t2.price, t2.pay_period, t2.speed, t2.link, t2.tarif_group_id from services s
            join tarifs t on t.id = s.tarif_id
            join tarifs t2 on t2.tarif_group_id = t.tarif_group_id
            where s.id = ' . $serviceId;
        $queryResult = $this->connection->query($query);
        return $queryResult !== false ? $queryResult->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    /**
     * @param int $serviceId
     * @param int $tariffId
     * @return bool
     * @throws Exception
     */
    public function setServiceTariff(int $serviceId, int $tariffId): bool
    {
        //Доп. проверку на то, что устанавливаемый тариф входит в ту же группу, что и предшествующий тариф сервиса
        $newTariff = (new TariffModel())->getOneById($tariffId, ['pay_period', 'id']);
        $oldTariffs = array_column($this->getServiceTariffGroup($serviceId),'id');
        if (!in_array($newTariff['id'], $oldTariffs, true)) {
            return false;
        }

        $newPayDay = DateTimeUtil::getDateFromCurrentMidNight('+' . $newTariff['pay_period'] . ' months')->format(DateTimeUtil::DB_DATE);

        $query = 'update services set tarif_id = ?, payday = ? where id = ?';
        $queryResult = $this->connection->prepare($query);
        foreach ([$tariffId, $newPayDay, $serviceId] as $key => $value) {
            $queryResult->bindValue($key + 1, $value);
        }
        $queryResult->execute();

        return (bool)$queryResult->rowCount();
    }

    /**
     * @param int $serviceId
     * @param array $variant
     * @return array
     */
    public function getOneById(int $serviceId, array $variant = ['*'])
    {
        $select = implode(',', $variant);
        $query = 'select ' . $select . ' from services where id = ' . $serviceId;
        $queryResult = $this->connection->query($query);
        return $queryResult !== false ? $queryResult->fetch(PDO::FETCH_ASSOC) : [];
    }
}