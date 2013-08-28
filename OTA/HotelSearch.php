<?php

namespace C2is\Bundle\OtaBundle\OTA;

use C2is\Bundle\OtaBundle\Exception\MissingParameterException;

/**
 * Class HotelSearch
 * @package Seh\Bundle\ReservitBundle\OTA
 */
class HotelSearch extends AbstractOtaMessage
{
    protected function getName()
    {
        return 'hotel_search';
    }

    protected function getRequiredOptions()
    {
        return array(
            'echo' => $this->generateEcho(),
            'timestamp' => $this->getTimestamp(),
            'ota',
            'requestor',
            'company_name',
        );
    }
}
