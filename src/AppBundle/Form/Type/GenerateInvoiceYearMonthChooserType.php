<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Model\GenerateInvoiceModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceYearMonthChooserType.
 *
 * @category FormType
 */
class GenerateInvoiceYearMonthChooserType extends GenerateReceiptYearMonthChooserType
{
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'generate_invoice_year_month_chooser';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => GenerateInvoiceModel::class,
            )
        );
    }
}
