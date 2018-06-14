<?php

namespace AppBundle\Enum;

/**
 * Class ReceiptYearMonthEnum.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class ReceiptYearMonthEnum
{
    const APP_FIRST_YEAR = 2017;

    const JANUARY = 1;
    const FEBRAURY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * @return array
     */
    public static function getMonthEnumArray()
    {
        return array(
            self::JANUARY => 'month.january',
            self::FEBRAURY => 'month.febraury',
            self::MARCH => 'month.march',
            self::APRIL => 'month.april',
            self::MAY => 'month.may',
            self::JUNE => 'month.june',
            self::JULY => 'month.july',
            self::AUGUST => 'month.august',
            self::SEPTEMBER => 'month.september',
            self::OCTOBER => 'month.october',
            self::NOVEMBER => 'month.november',
            self::DECEMBER => 'month.december',
        );
    }

    /**
     * @return array
     */
    public static function getTranslatedMonthEnumArray()
    {
        return array(
            self::JANUARY => 'gener',
            self::FEBRAURY => 'febrer',
            self::MARCH => 'març',
            self::APRIL => 'abril',
            self::MAY => 'maig',
            self::JUNE => 'juny',
            self::JULY => 'juliol',
            self::AUGUST => 'agost',
            self::SEPTEMBER => 'setembre',
            self::OCTOBER => 'octubre',
            self::NOVEMBER => 'novembre',
            self::DECEMBER => 'desembre',
        );
    }

    /**
     * @return array
     */
    public static function getYearEnumArray()
    {
        $result = array();
        $now = new \DateTime();
        $currentYear = intval($now->format('Y'));
//        TODO
//        $firstYearAvailable = $currentYear + 1;
//        $result["$firstYearAvailable"] = $firstYearAvailable;
        $steps = $currentYear - self::APP_FIRST_YEAR + 1;
        for ($i = 0; $i < $steps; ++$i) {
            $year = $currentYear - $i;
            $result["$year"] = $year;
        }

        return $result;
    }
}