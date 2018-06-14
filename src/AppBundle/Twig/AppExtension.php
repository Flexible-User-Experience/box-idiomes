<?php

namespace AppBundle\Twig;

use AppBundle\Entity\ClassGroup;
use AppBundle\Entity\Event;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Receipt;
use AppBundle\Entity\Tariff;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\TeacherAbsence;
use AppBundle\Entity\User;
use AppBundle\Enum\EventClassroomTypeEnum;
use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Enum\TariffTypeEnum;
use AppBundle\Enum\TeacherAbsenceTypeEnum;
use AppBundle\Enum\TeacherColorEnum;
use AppBundle\Enum\UserRolesEnum;
use AppBundle\Manager\ReceiptManager;

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
     * @var ReceiptManager
     */
    private $rm;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param ReceiptManager $rm
     */
    public function __construct(ReceiptManager $rm)
    {
        $this->rm = $rm;
    }

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
            new \Twig_SimpleFunction('is_receipt_invoiced', array($this, 'isReceiptInvoicedFunction')),
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
     * @param Receipt $receipt
     *
     * @return bool
     */
    public function isReceiptInvoicedFunction(Receipt $receipt)
    {
        return $this->rm->isReceiptInvoiced($receipt);
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
            new \Twig_SimpleFilter('draw_invoice_month', array($this, 'drawInvoiceMonth')),
            new \Twig_SimpleFilter('draw_money', array($this, 'drawMoney')),
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
                if (UserRolesEnum::ROLE_CMS == $role) {
                    $span .= '<span class="label label-warning" style="margin-right:10px">editor</span>';
                } elseif (UserRolesEnum::ROLE_ADMIN == $role) {
                    $span .= '<span class="label label-primary" style="margin-right:10px">administrador</span>';
                } elseif (UserRolesEnum::ROLE_SUPER_ADMIN == $role) {
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
            if (TeacherColorEnum::MAGENTA == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #EE388A"></span>';
            } elseif (TeacherColorEnum::BLUE == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #00ABE0"></span>';
            } elseif (TeacherColorEnum::YELLOW == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #FFCD38"></span>';
            } elseif (TeacherColorEnum::GREEN == $object->getColor()) {
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
     * @param Invoice $object
     *
     * @return string
     */
    public function drawInvoiceMonth($object)
    {
        return InvoiceYearMonthEnum::getTranslatedMonthEnumArray()[$object->getMonth()];
    }

    /**
     * @param Invoice $object
     *
     * @return string
     */
    public function drawMoney($object)
    {
        $result = '<span class="text text-info">0,00 €</span>';
        if (is_numeric($object)) {
            if ($object < 0) {
                $result = '<span class="text text-danger">'.number_format($object, 2, ',', '.').' €</span>';
            } elseif ($object > 0) {
                $result = '<span class="text text-success">'.number_format($object, 2, ',', '.').' €</span>';
            } else {
                $result = '<span class="text text-info">0,00 €</span>';
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
