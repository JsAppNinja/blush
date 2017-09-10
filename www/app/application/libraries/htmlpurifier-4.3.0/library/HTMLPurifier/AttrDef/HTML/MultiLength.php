<?php

/**
 * Validates a MultiLength as defined by the HTML spec.
 *
 * A multilength is either a integer (pixel count), a percentage, or
 * a relative number.
 */
class HTMLPurifier_AttrDef_HTML_MultiLength extends HTMLPurifier_AttrDef_HTML_Length
{

    public function validate($string, $config, $context)
    {

        $string = trim($string);
        if ($string === '') return FALSE;

        $parent_result = parent::validate($string, $config, $context);
        if ($parent_result !== FALSE) return $parent_result;

        $length = strlen($string);
        $last_char = $string[$length - 1];

        if ($last_char !== '*') return FALSE;

        $int = substr($string, 0, $length - 1);

        if ($int == '') return '*';
        if (!is_numeric($int)) return FALSE;

        $int = (int)$int;

        if ($int < 0) return FALSE;
        if ($int == 0) return '0';
        if ($int == 1) return '*';
        return ((string)$int) . '*';

    }

}

// vim: et sw=4 sts=4
