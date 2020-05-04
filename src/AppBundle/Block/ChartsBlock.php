<?php

namespace AppBundle\Block;

use AppBundle\Service\ChartsFactoryService;
use SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChartsBlock.
 *
 * @category Block
 */
class ChartsBlock extends AbstractBlockService
{
    /**
     * @var ChartsFactoryService
     */
    private $cfs;

    /**
     * ChartsBlock constructor.
     *
     * @param string|null               $name
     * @param ChartsFactoryService|null $cfs
     */
    public function __construct($name = null, EngineInterface $templating = null, $cfs = null)
    {
        parent::__construct($name, $templating);
        $this->cfs = $cfs;
    }

    /**
     * Execute.
     *
     * @return Response
     *
     * @throws InvalidColumnTypeException
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            [
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Charts',
                'dt' => $this->cfs->buildLastYearResultsChart()->toArray(),
            ],
            $response
        );
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return 'charts';
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'title' => 'Charts',
            'content' => 'Default content',
            'template' => ':Admin/Block:charts.html.twig',
        ]);
    }
}
