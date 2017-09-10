<?php

/**
 * Null cache object to use when no caching is on.
 */
class HTMLPurifier_DefinitionCache_Null extends HTMLPurifier_DefinitionCache
{

    public function add($def, $config)
    {
        return FALSE;
    }

    public function set($def, $config)
    {
        return FALSE;
    }

    public function replace($def, $config)
    {
        return FALSE;
    }

    public function remove($config)
    {
        return FALSE;
    }

    public function get($config)
    {
        return FALSE;
    }

    public function flush($config)
    {
        return FALSE;
    }

    public function cleanup($config)
    {
        return FALSE;
    }

}

// vim: et sw=4 sts=4
