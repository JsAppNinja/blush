<?php

/**
 * Validates shorthand CSS property background.
 * @warning Does not support url tokens that have internal spaces.
 */
class HTMLPurifier_AttrDef_CSS_Background extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of component validators.
     * @note See HTMLPurifier_AttrDef_Font::$info for a similar impl.
     */
    protected $info;

    public function __construct($config)
    {
        $def = $config->getCSSDefinition();
        $this->info['background-color'] = $def->info['background-color'];
        $this->info['background-image'] = $def->info['background-image'];
        $this->info['background-repeat'] = $def->info['background-repeat'];
        $this->info['background-attachment'] = $def->info['background-attachment'];
        $this->info['background-position'] = $def->info['background-position'];
    }

    public function validate($string, $config, $context)
    {

        // regular pre-processing
        $string = $this->parseCDATA($string);
        if ($string === '') return FALSE;

        // munge rgb() decl if necessary
        $string = $this->mungeRgb($string);

        // assumes URI doesn't have spaces in it
        $bits = explode(' ', strtolower($string)); // bits to process

        $caught = array();
        $caught['color'] = FALSE;
        $caught['image'] = FALSE;
        $caught['repeat'] = FALSE;
        $caught['attachment'] = FALSE;
        $caught['position'] = FALSE;

        $i = 0; // number of catches
        $none = FALSE;

        foreach ($bits as $bit) {
            if ($bit === '') continue;
            foreach ($caught as $key => $status) {
                if ($key != 'position') {
                    if ($status !== FALSE) continue;
                    $r = $this->info['background-' . $key]->validate($bit, $config, $context);
                } else {
                    $r = $bit;
                }
                if ($r === FALSE) continue;
                if ($key == 'position') {
                    if ($caught[$key] === FALSE) $caught[$key] = '';
                    $caught[$key] .= $r . ' ';
                } else {
                    $caught[$key] = $r;
                }
                $i++;
                break;
            }
        }

        if (!$i) return FALSE;
        if ($caught['position'] !== FALSE) {
            $caught['position'] = $this->info['background-position']->
              validate($caught['position'], $config, $context);
        }

        $ret = array();
        foreach ($caught as $value) {
            if ($value === FALSE) continue;
            $ret[] = $value;
        }

        if (empty($ret)) return FALSE;
        return implode(' ', $ret);

    }

}

// vim: et sw=4 sts=4
