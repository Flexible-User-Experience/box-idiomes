<?php

namespace AppBundle\Service;

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
    public function loadXlsReadOnlySpreadsheet($filepath)
    {
        $reader = new XlsReader();
        $reader->setReadDataOnly(true);

        return $reader->load($filepath);
    }
}
