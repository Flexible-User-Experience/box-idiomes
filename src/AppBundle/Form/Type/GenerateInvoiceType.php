<?php

namespace AppBundle\Form\Type;

use AppBundle\Enum\InvoiceYearMonthEnum;
use AppBundle\Form\Model\GenerateInvoiceModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceType.
 *
 * @category FormType
 */
class GenerateInvoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getYearEnumArray(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'month',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                    'choices_as_values' => false,
                )
            )
            ->add(
                'items',
                CollectionType::class,
                array(
                    'label' => 'backend.admin.invoice.items',
                    'allow_extra_fields' => false,
                    'cascade_validation' => true,
                    'required' => false,
                    'entry_type' => GenerateInvoiceItemType::class,
                    'by_reference' => false,
                    'entry_options' => array(
                        'label' => false,
                    ),
                )
            )
            ->add(
                'preview',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.invoice.preview_invoice',
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
            ->add(
                'generate',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.invoice.generate',
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
        return 'generate_invoice';
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
