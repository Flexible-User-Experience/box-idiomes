<?php

namespace AppBundle\Twig;

use AppBundle\Entity\ClassGroup;
use AppBundle\Entity\Event;
use AppBundle\Entity\Tariff;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\TeacherAbsence;
use AppBundle\Entity\User;
use AppBundle\Enum\EventClassroomTypeEnum;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Enum\TeacherAbsenceTypeEnum;
use AppBundle\Enum\TeacherColorEnum;
use AppBundle\Enum\UserRolesEnum;

/**
 * Class AppExtension.
 *
 * @category Twig
 *
 * @author   David Romaní <david@flux.cat>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * Twig Functions.
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('randomErrorText', array($this, 'randomErrorTextFunction')),
        );
    }

    /**
     * @param int $length length of Random String returned
     *
     * @return string
     */
    public function randomErrorTextFunction($length = 1024)
    {
        // Character List to Pick from
        $chrList = '012 3456 789 abcdef ghij klmno pqrs tuvwxyz ABCD EFGHIJK LMN OPQ RSTU VWXYZ';
        // Minimum/Maximum times to repeat character List to seed from
        $chrRepeatMin = 1; // Minimum times to repeat the seed string
        $chrRepeatMax = 30; // Maximum times to repeat the seed string

        return substr(str_shuffle(str_repeat($chrList, mt_rand($chrRepeatMin, $chrRepeatMax))), 1, $length);
    }

    /**
     * Twig Filters.
     */

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('draw_role_span', array($this, 'drawRoleSpan')),
            new \Twig_SimpleFilter('draw_teacher_color', array($this, 'drawTeacherColorSpan')),
            new \Twig_SimpleFilter('draw_teacher_absence_type', array($this, 'drawTeacherAbsenceType')),
            new \Twig_SimpleFilter('draw_class_group_color', array($this, 'drawClassGroupColorSpan')),
            new \Twig_SimpleFilter('draw_tariff_type', array($this, 'drawTariffType')),
            new \Twig_SimpleFilter('draw_event_classroom_type', array($this, 'drawEventClassroomType')),
        );
    }

    /**
     * @param User $object
     *
     * @return string
     */
    public function drawRoleSpan($object)
    {
        $span = '';
        if ($object instanceof User && count($object->getRoles()) > 0) {
            /** @var string $role */
            foreach ($object->getRoles() as $role) {
                if ($role == UserRolesEnum::ROLE_CMS) {
                    $span .= '<span class="label label-warning" style="margin-right:10px">editor</span>';
                } elseif ($role == UserRolesEnum::ROLE_ADMIN) {
                    $span .= '<span class="label label-primary" style="margin-right:10px">administrador</span>';
                } elseif ($role == UserRolesEnum::ROLE_SUPER_ADMIN) {
                    $span .= '<span class="label label-danger" style="margin-right:10px">superadministrador</span>';
                }
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    /**
     * @param Teacher $object
     *
     * @return string
     */
    public function drawTeacherColorSpan($object)
    {
        $span = '';
        if ($object instanceof Teacher) {
            if ($object->getColor() == TeacherColorEnum::MAGENTA) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #EE388A"></span>';
            } elseif ($object->getColor() == TeacherColorEnum::BLUE) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #00ABE0"></span>';
            } elseif ($object->getColor() == TeacherColorEnum::YELLOW) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #FFCD38"></span>';
            } elseif ($object->getColor() == TeacherColorEnum::GREEN) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #CEC533"></span>';
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    /**
     * @param ClassGroup $object
     *
     * @return string
     */
    public function drawClassGroupColorSpan($object)
    {
        return '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color:'.$object->getColor().'"></span>';
    }

    /**
     * @param TeacherAbsence $object
     *
     * @return string
     */
    public function drawTeacherAbsenceType($object)
    {
        return '<div class="text-left">'.TeacherAbsenceTypeEnum::getEnumArray()[$object->getType()].'</div>';
    }

    /**
     * @param Tariff $object
     *
     * @return string
     */
    public function drawTariffType($object)
    {
        return TariffTypeEnum::getEnumArray()[$object->getType()];
    }

    /**
     * @param Event $object
     *
     * @return string
     */
    public function drawEventClassroomType($object)
    {
        return EventClassroomTypeEnum::getEnumArray()[$object->getClassroom()];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
