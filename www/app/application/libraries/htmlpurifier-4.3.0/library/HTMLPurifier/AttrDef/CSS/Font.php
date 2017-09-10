<?php

/**
 * Validates shorthand CSS property font.
 */
class HTMLPurifier_AttrDef_CSS_Font extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of component validators.
     *
     * @note If we moved specific CSS property definitions to their own
     *       classes instead of having them be assembled at run time by
     *       CSSDefinition, this wouldn't be necessary.  We'd instantiate
     *       our own copies.
     */
    protected $info = array();

    public function __construct($config)
    {
        $def = $config->getCSSDefinition();
        $this->info['font-style'] = $def->info['font-style'];
        $this->info['font-variant'] = $def->info['font-variant'];
        $this->info['font-weight'] = $def->info['font-weight'];
        $this->info['font-size'] = $def->info['font-size'];
        $this->info['line-height'] = $def->info['line-height'];
        $this->info['font-family'] = $def->info['font-family'];
    }

    public function validate($string, $config, $context)
    {

        static $system_fonts = array(
            'caption'       => TRUE,
            'icon'          => TRUE,
            'menu'          => TRUE,
            'message-box'   => TRUE,
            'small-caption' => TRUE,
            'status-bar'    => TRUE
        );

        // regular pre-processing
        $string = $this->parseCDATA($string);
        if ($string === '') return FALSE;

        // check if it's one of the keywords
        $lowercase_string = strtolower($string);
        if (isset($system_fonts[$lowercase_string])) {
            return $lowercase_string;
        }

        $bits = explode(' ', $string); // bits to process
        $stage = 0; // this indicates what we're looking for
        $caught = array(); // which stage 0 properties have we caught?
        $stage_1 = array('font-style', 'font-variant', 'font-weight');
        $final = ''; // output

        for ($i = 0, $size = count($bits); $i < $size; $i++) {
            if ($bits[$i] === '') continue;
            switch ($stage) {

                // attempting to catch font-style, font-variant or font-weight
                case 0:
                    foreach ($stage_1 as $validator_name) {
                        if (isset($caught[$validator_name])) continue;
                        $r = $this->info[$validator_name]->validate(
                            $bits[$i], $config, $context);
                        if ($r !== FALSE) {
                            $final .= $r . ' ';
                            $caught[$validator_name] = TRUE;
                            break;
                        }
                    }
                    // all three caught, continue on
                    if (count($caught) >= 3) $stage = 1;
                    if ($r !== FALSE) break;

                // attempting to catch font-size and perhaps line-height
                case 1:
                    $found_slash = FALSE;
                    if (strpos($bits[$i], '/') !== FALSE) {
                        list($font_size, $line_height) =
                          explode('/', $bits[$i]);
                        if ($line_height === '') {
                            // ooh, there's a space after the slash!
                            $line_height = FALSE;
                            $found_slash = TRUE;
                        }
                    } else {
                        $font_size = $bits[$i];
                        $line_height = FALSE;
                    }
                    $r = $this->info['font-size']->validate(
                        $font_size, $config, $context);
                    if ($r !== FALSE) {
                        $final .= $r;
                        // attempt to catch line-height
                        if ($line_height === FALSE) {
                            // we need to scroll forward
                            for ($j = $i + 1; $j < $size; $j++) {
                                if ($bits[$j] === '') continue;
                                if ($bits[$j] === '/') {
                                    if ($found_slash) {
                                        return FALSE;
                                    } else {
                                        $found_slash = TRUE;
                                        continue;
                                    }
                                }
                                $line_height = $bits[$j];
                                break;
                            }
                        } else {
                            // slash already found
                            $found_slash = TRUE;
                            $j = $i;
                        }
                        if ($found_slash) {
                            $i = $j;
                            $r = $this->info['line-height']->validate(
                                $line_height, $config, $context);
                            if ($r !== FALSE) {
                                $final .= '/' . $r;
                            }
                        }
                        $final .= ' ';
                        $stage = 2;
                        break;
                    }
                    return FALSE;

                // attempting to catch font-family
                case 2:
                    $font_family =
                      implode(' ', array_slice($bits, $i, $size - $i));
                    $r = $this->info['font-family']->validate(
                        $font_family, $config, $context);
                    if ($r !== FALSE) {
                        $final .= $r . ' ';
                        // processing completed successfully
                        return rtrim($final);
                    }
                    return FALSE;
            }
        }
        return FALSE;
    }

}

// vim: et sw=4 sts=4
