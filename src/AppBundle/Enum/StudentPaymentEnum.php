<?php

namespace AppBundle\Enum;

/**
 * Class StudentPaymentEnum.
 *
 * @category Enum
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class StudentPaymentEnum
{
    const BANK_ACCOUNT_NUMBER = 0;
    const CASH = 1;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::BANK_ACCOUNT_NUMBER => 'backend.admin.student.bankAccountNumber',
            self::CASH => 'backend.admin.student.cash',
        );
    }
}
