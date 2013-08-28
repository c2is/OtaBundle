<?php

namespace C2is\Bundle\OtaBundle\Loader;

use C2is\Bundle\OtaBundle\Model\HotelSearchData;
use C2is\Bundle\OtaBundle\Model\RateRangeData;
use C2is\Bundle\OtaBundle\Model\RoomTypeData;

class HotelSearchLoader
{
    public function loadData($xml)
    {
        $data = array();
        $xml = simplexml_load_string($xml);

        foreach ($xml->Properties->Property as $result) {
            $hotelSearch = new HotelSearchData();
            $hotelSearch->chainCode = (string) $result->attributes()['ChainCode'];
            $hotelSearch->hotelCode = (string) $result->attributes()['HotelCode'];

            if ($result->RateRange) {
                $rateRange = new RateRangeData();
                $rateRange->currencyCode = (string) $result->RateRange->attributes()['CurrencyCode'];
                $rateRange->maxRate = (string) $result->RateRange->attributes()['MaxRate'];
                $rateRange->minRate = (string) $result->RateRange->attributes()['MinRate'];

                if ($result->TPA_Extensions) {
                    $roomTypes = array(
                        'min' => new RoomTypeData(),
                        'max' => new RoomTypeData(),
                    );
                    foreach ($result->TPA_Extensions->RateRangeLabels->RateRangeLabel as $rateRangeLabel) {
                        $lang = (string) $rateRangeLabel->attributes()['Langcode'];
                        $roomTypes['min']->roomTypeCode = (string) $rateRangeLabel->MinLabel->attributes()['RoomTypeCode'];
                        $roomTypes['min']->roomTypeCodeContext = (string) $rateRangeLabel->MinLabel->attributes()['RoomTypeCodeContext'];
                        $roomTypes['min']->roomTypeLabel[$lang] = (string) utf8_decode($rateRangeLabel->MinLabel[0]);

                        $roomTypes['max']->roomTypeCode = (string) $rateRangeLabel->MaxLabel->attributes()['RoomTypeCode'];
                        $roomTypes['max']->roomTypeCodeContext = (string) $rateRangeLabel->MaxLabel->attributes()['RoomTypeCodeContext'];
                        $roomTypes['max']->roomTypeLabel[$lang] = (string) utf8_decode($rateRangeLabel->MaxLabel[0]);
                    }

                    $rateRange->roomTypeMin = $roomTypes['min'];
                    $rateRange->roomTypeMax = $roomTypes['max'];
                }

                $hotelSearch->rateRange = $rateRange;
            }

            $data[] = $hotelSearch;
        }

        return $data;
    }
}