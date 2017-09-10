<?php

/**
 * XHTML 1.1 Ruby Annotation Module, defines elements that indicate
 * short runs of text alongside base text for annotation or pronounciation.
 */
class HTMLPurifier_HTMLModule_Ruby extends HTMLPurifier_HTMLModule
{

    public $name = 'Ruby';

    public function setup($config)
    {
        $this->addElement('ruby', 'Inline',
            'Custom: ((rb, (rt | (rp, rt, rp))) | (rbc, rtc, rtc?))',
            'Common');
        $this->addElement('rbc', FALSE, 'Required: rb', 'Common');
        $this->addElement('rtc', FALSE, 'Required: rt', 'Common');
        $rb = $this->addElement('rb', FALSE, 'Inline', 'Common');
        $rb->excludes = array('ruby' => TRUE);
        $rt = $this->addElement('rt', FALSE, 'Inline', 'Common', array('rbspan' => 'Number'));
        $rt->excludes = array('ruby' => TRUE);
        $this->addElement('rp', FALSE, 'Optional: #PCDATA', 'Common');
    }

}

// vim: et sw=4 sts=4
