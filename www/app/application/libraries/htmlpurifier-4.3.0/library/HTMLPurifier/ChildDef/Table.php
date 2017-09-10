<?php

/**
 * Definition for tables
 */
class HTMLPurifier_ChildDef_Table extends HTMLPurifier_ChildDef
{
    public $allow_empty = FALSE;
    public $type = 'table';
    public $elements = array('tr'    => TRUE, 'tbody' => TRUE, 'thead' => TRUE,
                             'tfoot' => TRUE, 'caption' => TRUE, 'colgroup' => TRUE, 'col' => TRUE);

    public function __construct()
    {
    }

    public function validateChildren($tokens_of_children, $config, $context)
    {
        if (empty($tokens_of_children)) return FALSE;

        // this ensures that the loop gets run one last time before closing
        // up. It's a little bit of a hack, but it works! Just make sure you
        // get rid of the token later.
        $tokens_of_children[] = FALSE;

        // only one of these elements is allowed in a table
        $caption = FALSE;
        $thead = FALSE;
        $tfoot = FALSE;

        // as many of these as you want
        $cols = array();
        $content = array();

        $nesting = 0; // current depth so we can determine nodes
        $is_collecting = FALSE; // are we globbing together tokens to package
        // into one of the collectors?
        $collection = array(); // collected nodes
        $tag_index = 0; // the first node might be whitespace,
        // so this tells us where the start tag is

        foreach ($tokens_of_children as $token) {
            $is_child = ($nesting == 0);

            if ($token === FALSE) {
                // terminating sequence started
            } elseif ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            // handle node collection
            if ($is_collecting) {
                if ($is_child) {
                    // okay, let's stash the tokens away
                    // first token tells us the type of the collection
                    switch ($collection[$tag_index]->name) {
                        case 'tr':
                        case 'tbody':
                            $content[] = $collection;
                            break;
                        case 'caption':
                            if ($caption !== FALSE) break;
                            $caption = $collection;
                            break;
                        case 'thead':
                        case 'tfoot':
                            // access the appropriate variable, $thead or $tfoot
                            $var = $collection[$tag_index]->name;
                            if ($$var === FALSE) {
                                $$var = $collection;
                            } else {
                                // transmutate the first and less entries into
                                // tbody tags, and then put into content
                                $collection[$tag_index]->name = 'tbody';
                                $collection[count($collection) - 1]->name = 'tbody';
                                $content[] = $collection;
                            }
                            break;
                        case 'colgroup':
                            $cols[] = $collection;
                            break;
                    }
                    $collection = array();
                    $is_collecting = FALSE;
                    $tag_index = 0;
                } else {
                    // add the node to the collection
                    $collection[] = $token;
                }
            }

            // terminate
            if ($token === FALSE) break;

            if ($is_child) {
                // determine what we're dealing with
                if ($token->name == 'col') {
                    // the only empty tag in the possie, we can handle it
                    // immediately
                    $cols[] = array_merge($collection, array($token));
                    $collection = array();
                    $tag_index = 0;
                    continue;
                }
                switch ($token->name) {
                    case 'caption':
                    case 'colgroup':
                    case 'thead':
                    case 'tfoot':
                    case 'tbody':
                    case 'tr':
                        $is_collecting = TRUE;
                        $collection[] = $token;
                        continue;
                    default:
                        if (!empty($token->is_whitespace)) {
                            $collection[] = $token;
                            $tag_index++;
                        }
                        continue;
                }
            }
        }

        if (empty($content)) return FALSE;

        $ret = array();
        if ($caption !== FALSE) $ret = array_merge($ret, $caption);
        if ($cols !== FALSE) foreach ($cols as $token_array) $ret = array_merge($ret, $token_array);
        if ($thead !== FALSE) $ret = array_merge($ret, $thead);
        if ($tfoot !== FALSE) $ret = array_merge($ret, $tfoot);
        foreach ($content as $token_array) $ret = array_merge($ret, $token_array);
        if (!empty($collection) && $is_collecting == FALSE) {
            // grab the trailing space
            $ret = array_merge($ret, $collection);
        }

        array_pop($tokens_of_children); // remove phantom token

        return ($ret === $tokens_of_children) ? TRUE : $ret;

    }
}

// vim: et sw=4 sts=4
