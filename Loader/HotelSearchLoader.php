<?php

namespace C2is\Bundle\OtaBundle\Loader;

use C2is\Bundle\OtaBundle\Model\HotelSearch as HotelSearchClass;
use C2is\Bundle\OtaBundle\Model\RateRange;

class HotelSearch
{
    public function loadData($xml)
    {
        $data = array();
        $xml = simplexml_load_string($xml);

        foreach ($xml->Properties->Property as $result) {
            $hotelSearch = new HotelSearchClass();
            $hotelSearch->chainCode = (string) $result->attributes()['ChainCode'];
            $hotelSearch->hotelCode = (string) $result->attributes()['HotelCode'];

            if ($result->RateRange) {
                $rateRange = new RateRange();
                $rateRange->currencyCode = (string) $result->RateRange->attributes()['CurrencyCode'];
                $rateRange->maxRate = (string) $result->RateRange->attributes()['MaxRate'];
                $rateRange->minRate = (string) $result->RateRange->attributes()['MinRate'];
                var_dump($result->RateRange);
            }
            die;
        }

        return $data;
    }
}