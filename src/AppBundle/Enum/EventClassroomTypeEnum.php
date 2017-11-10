<?php

namespace AppBundle\Enum;

/**
 * Class EventClassroomTypeEnum.
 *
 * @category Enum
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class EventClassroomTypeEnum
{
    const CLASSROOM_1 = 0;
    const CLASSROOM_2 = 1;
    const CLASSROOM_3 = 2;
    const CLASSROOM_4 = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::CLASSROOM_1 => 'backend.admin.event.classroom_1',
            self::CLASSROOM_2 => 'backend.admin.event.classroom_2',
            self::CLASSROOM_3 => 'backend.admin.event.classroom_3',
            self::CLASSROOM_4 => 'backend.admin.event.classroom_4',
        );
    }
}
