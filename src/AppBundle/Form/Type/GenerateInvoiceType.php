<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class GenerateInvoiceType.
 *
 * @category FormType
 */
class GenerateInvoiceType extends GenerateInvoiceYearMonthChooserType
{
    /**
     * @var RouterInterface
     */
    private $rs;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceType constructor.
     *
     * @param RouterInterface $rs
     */
    public function __construct(RouterInterface $rs)
    {
        $this->rs = $rs;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('preview')
            ->add(
                'items',
                CollectionType::class,
                array(
                    'label' => 'backend.admin.invoice.items',
                    'allow_extra_fields' => true,
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
                'generate',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.invoice.generate',
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
            ->setAction($this->rs->generate('admin_app_invoice_creator'))
        ;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'generate_invoice';
    }
}
