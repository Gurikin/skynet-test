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
     * @param int $userId
     * @param int $serviceId
     * @return array
     */
    public function getUserServiceTarifGroup(int $userId, int $serviceId): array
    {
        $query = 'select t2.* from services s
            join tarifs t on t.id = s.tarif_id
            join tarifs t2 on t2.tarif_group_id = t.tarif_group_id
            where s.user_id = ' . $userId . ' and s.id = ' . $serviceId;
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}