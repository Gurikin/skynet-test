<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 30.04.2020 1:26
 */


class DateTimeUtil
{
    public const TIMESTAMP_ZONE = 'UO';
    public const DB_DATE = 'Y-m-d';

    /**
     * @param string $period
     * @return DateTime
     * @throws Exception
     */
    public static function getDateFromCurrentMidNight(string $period): DateTime
    {
        return (new DateTime('today midnight +' . $period));
    }
}