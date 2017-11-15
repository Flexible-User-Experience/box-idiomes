<?php

namespace AppBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventCalendarBlock.
 *
 * @category Block
 *
 * @author Wils Iglesias <wiglesias83@gmail.com>
 */
class EventCalendarBlock extends AbstractBlockService
{
    /**
     * Execute.
     *
     * @param BlockContextInterface $blockContext
     * @param Response|null         $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Notificacions',
            ),
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
        return 'event_calendar';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title' => 'Resum',
            'content' => 'Default content',
            'template' => ':Admin/Block:calendar.html.twig',
        ));
    }
}
