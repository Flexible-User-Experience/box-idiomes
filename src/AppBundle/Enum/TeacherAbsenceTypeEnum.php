<?php

namespace AppBundle\Enum;

/**
 * Class TeacherAbsenceTypeEnum.
 *
 * @category Enum
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TeacherAbsenceTypeEnum
{
    const PERSONAL_ISSUES = 0;
    const TRAINING = 1;
    const OTHER_ISSUES = 2;
    const HOLIDAYS = 3;
    const SICK_LEAVE = 4;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::PERSONAL_ISSUES => 'Assumptes personals',
            self::TRAINING => 'Formació',
            self::OTHER_ISSUES => 'Altres motius',
            self::HOLIDAYS => 'Vacances',
            self::SICK_LEAVE => 'Baixa laboral',
        );
    }
}
