<?php

namespace AppBundle\Enum;

/**
 * TeacherColorEnum class.
 *
 * @category Enum
 *
 * @author   David Romaní <david@flux.cat>
 */
class TeacherColorEnum
{
    const MAGENTA = 0;
    const BLUE = 1;
    const YELLOW = 2;
    const GREEN = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::MAGENTA => 'color.magenta',
            self::BLUE => 'color.blue',
            self::YELLOW => 'color.yellow',
            self::GREEN => 'color.green',
        );
    }
}
