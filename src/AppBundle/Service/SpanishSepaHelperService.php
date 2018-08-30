<?php

namespace AppBundle\Service;

/**
 * Class SpanishSepaHelperService.
 *
 * @category Service
 */
class SpanishSepaHelperService
{
    const COUNTRY_CODE = 'ES';
    const SUFIX = '000';
    const MINFIX = '00';

    /**
     * @param string $nif
     *
     * @return string
     */
    public function getSpanishCreditorIdFromNif($nif)
    {
        $composition = $nif.self::COUNTRY_CODE.self::MINFIX;
        $conversionWithoutLetters = $this->letterToNumberConversion($composition);
        $controlDigits = $this->controlDigitsCalculation($conversionWithoutLetters);

        return self::COUNTRY_CODE.$controlDigits.self::SUFIX.$nif;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function letterToNumberConversion($value)
    {
        $result = str_replace('A', '10', $value);
        $result = str_replace('B', '11', $result);
        $result = str_replace('C', '12', $result);
        $result = str_replace('D', '13', $result);
        $result = str_replace('E', '14', $result);
        $result = str_replace('F', '15', $result);
        $result = str_replace('G', '16', $result);
        $result = str_replace('H', '17', $result);
        $result = str_replace('I', '18', $result);
        $result = str_replace('J', '19', $result);
        $result = str_replace('K', '20', $result);
        $result = str_replace('L', '21', $result);
        $result = str_replace('M', '22', $result);
        $result = str_replace('N', '23', $result);
        $result = str_replace('O', '24', $result);
        $result = str_replace('P', '25', $result);
        $result = str_replace('Q', '26', $result);
        $result = str_replace('R', '27', $result);
        $result = str_replace('S', '28', $result);
        $result = str_replace('T', '29', $result);
        $result = str_replace('U', '30', $result);
        $result = str_replace('V', '31', $result);
        $result = str_replace('W', '32', $result);
        $result = str_replace('X', '33', $result);
        $result = str_replace('Y', '34', $result);
        $result = str_replace('Z', '35', $result);

        return $result;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function controlDigitsCalculation($value)
    {
        $result = intval($value);
        $result = 98 - ($result % 97);

        if ($result < 10) {
            $result = '0'.strval($result);
        } else {
            $result = strval($result);
        }

        return $result;
    }
}
