<?php

namespace AppBundle\Form\Type;

use AppBundle\Enum\ReceiptYearMonthEnum;
use AppBundle\Form\Model\GenerateReceiptModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateReceiptYearMonthChooserType.
 *
 * @category FormType
 */
class GenerateReceiptYearMonthChooserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => ReceiptYearMonthEnum::getYearEnumArray(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'month',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => ReceiptYearMonthEnum::getMonthEnumArray(),
                    'choices_as_values' => false,
                )
            )
            ->add(
                'preview',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.receipt.preview_invoice',
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'generate_receipt_year_month_chooser';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => GenerateReceiptModel::class,
            )
        );
    }
}
