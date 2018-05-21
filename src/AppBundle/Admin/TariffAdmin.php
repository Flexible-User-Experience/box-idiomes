<?php

namespace AppBundle\Admin;

use AppBundle\Enum\TariffTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class TariffAdmin.
 *
 * @category Admin
 *
 * @author   Wils Iglesias <wiglesias83@gmail.com>
 */
class TariffAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Tarriff';
    protected $baseRoutePattern = 'billings/tariff';
    protected $datagridValues = array(
        '_sort_by' => 'year',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('delete')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.tariff.year',
                    'required' => true,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.tariff.name',
                    'required' => false,
                )
            )
            ->add(
                'price',
                null,
                array(
                    'label' => 'backend.admin.tariff.price',
                    'required' => true,
                )
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'label' => 'backend.admin.teacher_absence.type',
                    'choices' => TariffTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.tariff.year',
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label' => 'backend.admin.tariff.type',
                ),
                ChoiceType::class,
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => TariffTypeEnum::getEnumArray(),
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.tariff.name',
                )
            )
            ->add(
                'price',
                null,
                array(
                    'label' => 'backend.admin.tariff.price',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'year',
                null,
                array(
                    'label' => 'backend.admin.tariff.year',
                    'editable' => true,
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label' => 'backend.admin.tariff.type',
                    'template' => '::Admin/Cells/list__cell_tariff_type.html.twig',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.tariff.name',
                    'editable' => true,
                )
            )
            ->add(
                'price',
                'decimal',
                array(
                    'label' => 'backend.admin.tariff.price',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                    'label' => 'backend.admin.actions',
                )
            )
        ;
    }
}
