<?php

/**
 * Validates an integer representation of pixels according to the HTML spec.
 */
class HTMLPurifier_AttrDef_HTML_Pixels extends HTMLPurifier_AttrDef
{

    protected $max;

    public function __construct($max = NULL)
    {
        $this->max = $max;
    }

    public function validate($string, $config, $context)
    {

        $string = trim($string);
        if ($string === '0') return $string;
        if ($string === '') return FALSE;
        $length = strlen($string);
        if (substr($string, $length - 2) == 'px') {
            $string = substr($string, 0, $length - 2);
        }
        if (!is_numeric($string)) return FALSE;
        $int = (int)$string;

        if ($int < 0) return '0';

        // upper-bound value, extremely high values can
        // crash operating systems, see <http://ha.ckers.org/imagecrash.html>
        // WARNING, above link WILL crash you if you're using Windows

        if ($this->max !== NULL && $int > $this->max) return (string)$this->max;

        return (string)$int;

    }

    public function make($string)
    {
        if ($string === '') $max = NULL;
        else $max = (int)$string;
        $class = get_class($this);
        return new $class($max);
    }

}

// vim: et sw=4 sts=4
