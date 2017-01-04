<?php

namespace AppBundle\Enum;

/**
 * TeacherColorEnum class
 *
 * @category Enum
 * @package  AppBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class TeacherColorEnum
{
    const MAGENTA = 0;
    const BLUE    = 1;
    const YELLOW  = 2;
    const GREEN   = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::MAGENTA => 'magenta',
            self::BLUE    => 'blue',
            self::YELLOW  => 'yellow',
            self::GREEN   => 'green',
        );
    }
}
