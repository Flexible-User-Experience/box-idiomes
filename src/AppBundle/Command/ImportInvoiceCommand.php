<?php

namespace AppBundle\Command;

use AppBundle\Entity\Invoice;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportInvoiceCommand.
 *
 * @category Command
 */
class ImportInvoiceCommand extends BaseImportCommand
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('app:import:invoice')
            ->setDescription('Import an invoice from XLS file')
            ->addArgument(
                'filepath',
                InputArgument::REQUIRED,
                'The XLS file path to import in database'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will persist invoices into database'
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
        // Welcome
        $output->writeln('<info>Welcome to '.$this->getName().' command</info>');
        if ($input->getOption('force')) {
            $output->writeln('<comment>--force option enabled (this option persists invoices into database)</comment>');
        }

        // Validate arguments
        $filename = $input->getArgument('filepath');
        if (!$this->getFilesystem()->exists($filename)) {
            throw new \InvalidArgumentException('The file '.$filename.' does not exist');
        }

        // Main loop
        $output->writeln('Loading data, please wait...');

        // Command logic
        $dtStart = new \DateTime();
        $index = 0;
        $created = 0;
        $em = $this->getEntityManager();
        ini_set('auto_detect_line_endings', true);

        // EOF
        $output->writeln('<info>EOF.</info>');
    }
}
