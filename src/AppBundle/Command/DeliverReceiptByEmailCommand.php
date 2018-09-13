<?php

namespace AppBundle\Command;

use AppBundle\Entity\Receipt;
use AppBundle\Service\NotificationService;
use AppBundle\Service\ReceiptPdfBuilderService;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class DeliverReceiptByEmailCommand.
 *
 * @category Command
 */
class DeliverReceiptByEmailCommand extends ContainerAwareCommand
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('app:deliver:receipt')
            ->setDescription('Deliver a receipt previously generated by email')
            ->addArgument(
                'receipt',
                InputArgument::REQUIRED,
                'The receipt ID stored in database'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will deliver the email'
            )
        ;
    }

    /**
     * Execute command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Welcome to '.$this->getName().' command</info>');
        /** @var Receipt|null $receipt */
        $receipt = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Receipt')->find(intval($input->getArgument('receipt')));
        if ($receipt) {
            $output->write('building PDF receipt number '.$receipt->getReceiptNumber().'... ');
            /** @var Logger $logger */
            $logger = $this->getContainer()->get('monolog.logger.email');
            /** @var ReceiptPdfBuilderService $rps */
            $rps = $this->getContainer()->get('app.receipt_pdf_builder');
            $pdf = $rps->build($receipt);
            $output->writeln('<info>OK</info>');
            $logger->info('[DRBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully build.');
            if ($input->getOption('force')) {
                $output->write('delivering PDF receipt number '.$receipt->getReceiptNumber().'... ');
                /** @var NotificationService $messenger */
                $messenger = $this->getContainer()->get('app.notification');
                if ($receipt->getMainEmail()) {
                    $result = $messenger->sendReceiptPdfNotification($receipt, $pdf);
                    if (0 === $result) {
                        $output->writeln('<error>KO</error>');
                        $logger->error('[DRBEC] delivering PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' failed.');
                    } else {
                        $output->writeln('<info>OK</info>');
                        $logger->info('[DRBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully delivered.');
                    }
                } else {
                    $output->writeln('<comment>KO</comment>');
                    $logger->error('[DRBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' not delivered. Missing email in '.$receipt->getMainSubject()->getFullCanonicalName().'.');
                }
            }
        } else {
            $output->writeln('<error>No receipt with ID#'.intval($input->getArgument('receipt')).' found. Nothing send.</error>');
        }

        $output->writeln('<info>EOF.</info>');
    }
}
