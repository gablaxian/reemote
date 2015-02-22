<?php namespace Amu\Reemote\Output;

interface OutputInterface {

    /**
     * Outputs text
     * 
     * @param string $text
     */
    function write($text);

    /**
     * Outputs text followed by a line break
     * 
     * @param string $text
     */
    function writeln($test = '');

}