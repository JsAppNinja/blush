<?php

/**
 * Validates a color according to the HTML spec.
 */
class HTMLPurifier_AttrDef_HTML_Color extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context)
    {

        static $colors = NULL;
        if ($colors === NULL) $colors = $config->get('Core.ColorKeywords');

        $string = trim($string);

        if (empty($string)) return FALSE;
        if (isset($colors[$string])) return $colors[$string];
        if ($string[0] === '#') $hex = substr($string, 1);
        else $hex = $string;

        $length = strlen($hex);
        if ($length !== 3 && $length !== 6) return FALSE;
        if (!ctype_xdigit($hex)) return FALSE;
        if ($length === 3) $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];

        return "#$hex";

    }

}

// vim: et sw=4 sts=4
