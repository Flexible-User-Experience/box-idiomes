<?php

namespace AppBundle\Enum;

/**
 * Class InvoiceYearMonthEnum.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class InvoiceYearMonthEnum
{
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
            self::JANUARY => 'backend.admin.invoice.month.january',
            self::FEBRAURY => 'backend.admin.invoice.month.febraury',
            self::MARCH => 'backend.admin.invoice.month.march',
            self::APRIL => 'backend.admin.invoice.month.april',
            self::MAY => 'backend.admin.invoice.month.may',
            self::JUNE => 'backend.admin.invoice.month.june',
            self::JULY => 'backend.admin.invoice.month.july',
            self::AUGUST => 'backend.admin.invoice.month.august',
            self::SEPTEMBER => 'backend.admin.invoice.month.september',
            self::OCTOBER => 'backend.admin.invoice.month.october',
            self::NOVEMBER => 'backend.admin.invoice.month.november',
            self::DECEMBER => 'backend.admin.invoice.month.december',
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
        $steps = $currentYear - 2017 + 1;
        for ($i = 0; $i < $steps; $i++) {
            $year = $currentYear - $i;
            $result["$year"] = $year;
        }

        return $result;
    }
}
