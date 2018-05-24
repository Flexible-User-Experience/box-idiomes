<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Student;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceItemType.
 *
 * @category FormType
 *
 * @author   David Romaní <david@flux.cat>
 */
class GenerateInvoiceItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'student',
                EntityType::class,
                array(
                    'label' => 'backend.admin.student.student',
                    'class' => Student::class,
                    'required' => true,
                )
            )
            ->add(
                'units',
                NumberType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.units',
                    'scale' => 1,
                    'grouping' => true,
                    'required' => true,
                )
            )
            ->add(
                'unitPrice',
                NumberType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                    'scale' => 2,
                    'grouping' => true,
                    'required' => true,
                )
            )
            ->add(
                'discount',
                NumberType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.discount',
                    'scale' => 2,
                    'grouping' => true,
                    'required' => true,
                )
            )
            ->add(
                'isReadyToGenerate',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.isReadyToGenerate',
                    'required' => true,
                )
            )
            ->add(
                'isPreviouslyGenerated',
                CheckboxType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.isPreviouslyGenerated',
                    'required' => true,
                )
            )
        ;
    }

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
