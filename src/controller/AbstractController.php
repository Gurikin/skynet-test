<?php
/**
 * Created by Igor Banchikov [gurikin]
 * At 29.04.2020 21:36
 */


/**
 * Class AbstractController
 */
abstract class AbstractController implements IController
{

    /**
     * @param int $status
     * @param string $type
     * @param string $content
     * @return string
     */
    protected function response(int $status, string $type, string $content): string
    {
        return '';
    }
}