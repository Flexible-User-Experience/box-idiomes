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
    const TARIFF_1 = 0;
    const TARIFF_2 = 1;
    const TARIFF_3 = 2;
    const TARIFF_4 = 3;
    const TARIFF_5 = 4;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::TARIFF_1 => '1h/setmana',
            self::TARIFF_2 => '2h/setmana',
            self::TARIFF_3 => '3h/setmana',
            self::TARIFF_4 => '1,5/setmana',
            self::TARIFF_5 => '18€/hora',
        );
    }
}
