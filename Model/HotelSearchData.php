<?php

namespace C2is\Bundle\OtaBundle\Model;

/**
 * Data class used to hold the results of an HotelSearch request.
 *
 * Class HotelSearchData
 * @package C2is\Bundle\OtaBundle\Model
 */
class HotelSearchData
{
    /**
     * @var string The chain code
     */
    public $chainCode;

    /**
     * @var string The hotel code
     */
    public $hotelCode;

    /**
     * @var RateRange The rate range
     */
    public $rateRange;
}
