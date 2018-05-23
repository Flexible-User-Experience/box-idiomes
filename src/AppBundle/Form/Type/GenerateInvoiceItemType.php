<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Student;
use AppBundle\Form\Model\GenerateInvoiceItemModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                IntegerType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.units',
                    'required' => true,
                )
            )
            ->add(
                'unitPrice',
                IntegerType::class,
                array(
                    'label' => 'backend.admin.invoiceLine.priceUnit',
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
