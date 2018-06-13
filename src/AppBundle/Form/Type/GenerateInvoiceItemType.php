<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\Model\GenerateInvoiceItemModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceItemType.
 *
 * @category FormType
 */
class GenerateInvoiceItemType extends GenerateReceiptItemType
{
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'generate_invoice_item';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => GenerateInvoiceItemModel::class,
            )
        );
    }
}
