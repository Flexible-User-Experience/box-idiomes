<?php

namespace AppBundle\Enum;

/**
 * Class StudentPaymentEnum.
 *
 * @category Enum
 */
class StudentPaymentEnum
{
    const BANK_ACCOUNT_NUMBER = 0;
    const CASH = 1;
    const BANK_TRANSFER = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::BANK_ACCOUNT_NUMBER => 'backend.admin.student.bank',
            self::CASH => 'backend.admin.student.cash',
            self::BANK_TRANSFER => 'backend.admin.student.transfer',
        );
    }
}
