<?php

/**
 * Validates the value for the CSS property text-decoration
 * @note This class could be generalized into a version that acts sort of
 *       like Enum except you can compound the allowed values.
 */
class HTMLPurifier_AttrDef_CSS_TextDecoration extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context)
    {

        static $allowed_values = array(
            'line-through' => TRUE,
            'overline'     => TRUE,
            'underline'    => TRUE,
        );

        $string = strtolower($this->parseCDATA($string));

        if ($string === 'none') return $string;

        $parts = explode(' ', $string);
        $final = '';
        foreach ($parts as $part) {
            if (isset($allowed_values[$part])) {
                $final .= $part . ' ';
            }
        }
        $final = rtrim($final);
        if ($final === '') return FALSE;
        return $final;

    }

}

// vim: et sw=4 sts=4
