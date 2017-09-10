<?php

/**
 * Validates a number as defined by the CSS spec.
 */
class HTMLPurifier_AttrDef_CSS_Number extends HTMLPurifier_AttrDef
{

    /**
     * Bool indicating whether or not only positive values allowed.
     */
    protected $non_negative = FALSE;

    /**
     * @param $non_negative Bool indicating whether negatives are forbidden
     */
    public function __construct($non_negative = FALSE)
    {
        $this->non_negative = $non_negative;
    }

    /**
     * @warning Some contexts do not pass $config, $context. These
     *          variables should not be used without checking HTMLPurifier_Length
     */
    public function validate($number, $config, $context)
    {

        $number = $this->parseCDATA($number);

        if ($number === '') return FALSE;
        if ($number === '0') return '0';

        $sign = '';
        switch ($number[0]) {
            case '-':
                if ($this->non_negative) return FALSE;
                $sign = '-';
            case '+':
                $number = substr($number, 1);
        }

        if (ctype_digit($number)) {
            $number = ltrim($number, '0');
            return $number ? $sign . $number : '0';
        }

        // Period is the only non-numeric character allowed
        if (strpos($number, '.') === FALSE) return FALSE;

        list($left, $right) = explode('.', $number, 2);

        if ($left === '' && $right === '') return FALSE;
        if ($left !== '' && !ctype_digit($left)) return FALSE;

        $left = ltrim($left, '0');
        $right = rtrim($right, '0');

        if ($right === '') {
            return $left ? $sign . $left : '0';
        } elseif (!ctype_digit($right)) {
            return FALSE;
        }

        return $sign . $left . '.' . $right;

    }

}

// vim: et sw=4 sts=4
