<?php

namespace AppBundle\Service;

use AppBundle\Xls\ReadFilterXls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class SpreadsheetService.
 *
 * @category Service
 */
class SpreadsheetService
{
    /**
     * @param string $filepath
     *
     * @return Spreadsheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadSpreadsheet($filepath)
    {
        return IOFactory::load($filepath);
    }

    /**
     * @param string $filepath
     *
     * @return Spreadsheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadXlsSpreadsheetReadOnly($filepath)
    {
        $reader = new XlsReader();
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ReadFilterXls());

        return $reader->load($filepath);
    }

    /**
     * @param string                $filepath
     * @param array|string[]|string $worksheets
     *
     * @return Spreadsheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadWorksheetsXlsSpreadsheetReadOnly($filepath, $worksheets)
    {
        $reader = new XlsReader();
        $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly($worksheets);
        $reader->setReadFilter(new ReadFilterXls());

        return $reader->load($filepath);
    }
}
