<?php

/**
 * XHTML 1.1 Presentation Module, defines simple presentation-related
 * markup. Text Extension Module.
 * @note The official XML Schema and DTD specs further divide this into
 *       two modules:
 *          - Block Presentation (hr)
 *          - Inline Presentation (b, big, i, small, sub, sup, tt)
 *       We have chosen not to heed this distinction, as content_sets
 *       provides satisfactory disambiguation.
 */
class HTMLPurifier_HTMLModule_Presentation extends HTMLPurifier_HTMLModule
{

    public $name = 'Presentation';

    public function setup($config)
    {
        $this->addElement('hr', 'Block', 'Empty', 'Common');
        $this->addElement('sub', 'Inline', 'Inline', 'Common');
        $this->addElement('sup', 'Inline', 'Inline', 'Common');
        $b = $this->addElement('b', 'Inline', 'Inline', 'Common');
        $b->formatting = TRUE;
        $big = $this->addElement('big', 'Inline', 'Inline', 'Common');
        $big->formatting = TRUE;
        $i = $this->addElement('i', 'Inline', 'Inline', 'Common');
        $i->formatting = TRUE;
        $small = $this->addElement('small', 'Inline', 'Inline', 'Common');
        $small->formatting = TRUE;
        $tt = $this->addElement('tt', 'Inline', 'Inline', 'Common');
        $tt->formatting = TRUE;
    }

}

// vim: et sw=4 sts=4
