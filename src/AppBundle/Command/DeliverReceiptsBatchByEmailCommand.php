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
 * Class DeliverReceiptsBatchByEmailCommand.
 *
 * @category Command
 */
class DeliverReceiptsBatchByEmailCommand extends ContainerAwareCommand
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('app:deliver:receipts:batch')
            ->setDescription('Deliver a receipts batch by email')
            ->addArgument(
                'receipts',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'The receipts ID list stored in database to deliver'
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
        /** @var Receipt[]|array $receipts */
        $receipts = $this->getContainer()->get('doctrine')->getRepository('AppBundle:Receipt')->findByIdsArray($input->getArgument('receipts'));
        if (count($receipts) > 0) {
            /** @var Logger $logger */
            $logger = $this->getContainer()->get('monolog.logger.email');
            /** @var ReceiptPdfBuilderService $rps */
            $rps = $this->getContainer()->get('app.receipt_pdf_builder');
            /** @var Receipt $receipt */
            foreach ($receipts as $receipt) {
                $output->write('building PDF receipt number '.$receipt->getReceiptNumber().'... ');
                $pdf = $rps->build($receipt);
                $output->writeln('<info>OK</info>');
                $logger->info('[DRBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully build.');
                if ($input->getOption('force')) {
                    $output->write('delivering PDF receipt number '.$receipt->getReceiptNumber().'... ');
                    /** @var NotificationService $messenger */
                    $messenger = $this->getContainer()->get('app.notification');
                    $result = $messenger->sendReceiptPdfNotification($receipt, $pdf);
                    if (0 === $result) {
                        $output->writeln('<error>KO</error>');
                        $logger->error('[DRBEC] delivering PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' failed.');
                    } else {
                        $output->writeln('<info>OK</info>');
                        $logger->info('[DRBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully delivered.');
                    }
                }
            }
        } else {
            $output->writeln('<error>No receipts with IDs# '.implode(', ', $input->getArgument('receipt')).' found. Nothing send.</error>');
        }

        $output->writeln('<info>EOF.</info>');
    }
}
