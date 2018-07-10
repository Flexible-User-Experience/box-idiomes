<?php

namespace AppBundle\Enum;

/**
 * Class TariffTypeEnum.
 *
 * @category Enum
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TariffTypeEnum
{
    const TARIFF_ONE_HOUR_PER_WEEK = 0;
    const TARIFF_TWO_HOUR_PER_WEEK = 1;
    const TARIFF_THREE_HOUR_PER_WEEK = 2;
    const TARIFF_HALF_HOUR_PER_WEEK = 3;
    const TARIFF_PRIVATE_LESSON_PER_HOUR = 4;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::TARIFF_ONE_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_1',
            self::TARIFF_TWO_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_2',
            self::TARIFF_THREE_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_3',
            self::TARIFF_HALF_HOUR_PER_WEEK => 'backend.admin.tariff.tariff_4',
            self::TARIFF_PRIVATE_LESSON_PER_HOUR => 'backend.admin.tariff.tariff_5',
        );
    }

    /**
     * @return array
     */
    public static function getTranslatedEnumArray()
    {
        return array(
            self::TARIFF_ONE_HOUR_PER_WEEK => '1h /setmana',
            self::TARIFF_TWO_HOUR_PER_WEEK => '2h / setmana',
            self::TARIFF_THREE_HOUR_PER_WEEK => '3h / setmana',
            self::TARIFF_HALF_HOUR_PER_WEEK => '1,5h / setmana',
            self::TARIFF_PRIVATE_LESSON_PER_HOUR => 'hora particular',
        );
    }
}
