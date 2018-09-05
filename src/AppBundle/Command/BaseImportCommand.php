<?php

namespace AppBundle\Command;

use AppBundle\Service\SpreadsheetService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Validator\DataCollectingValidator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class BaseImportCommand.
 */
abstract class BaseImportCommand extends ContainerAwareCommand
{
    const CSV_DELIMITER = ';';
    const CSV_ENCLOSURE = '"';
    const CSV_ESCAPE = '\\';

    const IMPORT_BATCH_WINDOW = 100; // Doctrine flush & clear iteration trigger

    /**
     * Methods.
     */

    /**
     * Read line from CSV file.
     *
     * @param resource $fr        a valid file pointer to a file successfully opened
     * @param string   $delimiter
     * @param string   $enclosure
     * @param string   $escape
     *
     * @return array
     */
    public function readCSVLine($fr, $delimiter = self::CSV_DELIMITER, $enclosure = self::CSV_ENCLOSURE, $escape = self::CSV_ESCAPE)
    {
        return fgetcsv($fr, 0, $delimiter, $enclosure, $escape);
    }

    /**
     * Load column data from searched array if exists, else throws an exception.
     *
     * @param $colIndex
     * @param $searchArray
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function loadColumnData($colIndex, $searchArray)
    {
        if (!array_key_exists($colIndex, $searchArray)) {
            throw new \Exception($colIndex." doesn't exists");
        }

        return '<null>' != $searchArray[$colIndex] ? $searchArray[$colIndex] : null;
    }

    /**
     * Get current timestamp string with format Y/m/d H:i:s.
     *
     * @return string
     */
    public function getTS()
    {
        $cm = new \DateTime();

        return $cm->format('Y/m/d H:i:s · ');
    }

    /**
     * @param OutputInterface $output
     */
    public function printBeginCommand(OutputInterface $output)
    {
        $output->writeln('<info>------ BEGIN COMMAND ------</info>');
    }

    /**
     * @param OutputInterface $output
     */
    public function printEndCommand(OutputInterface $output)
    {
        $output->writeln('<info>------- END COMMAND -------</info>');
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->getContainer()->get('filesystem');
    }

    /**
     * @return SpreadsheetService
     */
    public function getSpreadsheetManager()
    {
        return $this->getContainer()->get('app.spreadsheet_factory');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return DataCollectingValidator
     */
    public function getValidator()
    {
        return $this->getContainer()->get('validator');
    }
}
