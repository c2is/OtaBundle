<?php

namespace C2is\Bundle\OtaBundle\Model;

/**
 * Data class used to hold data for a room type.
 *
 * Class RoomTypeData
 * @package C2is\Bundle\OtaBundle\Model
 */
class RoomTypeData
{
    /**
     * @var string The room type code.
     */
    public $roomTypeCode;

    /**
     * @var string The room type code context.
     */
    public $roomTypeCodeContext;

    /**
     * @var array The localized labels for the room type.
     */
    public $roomTypeLabel;
}