<?php namespace Amu\Reemote\Output;

class NullOutput implements OutputInterface {

    public function write($text)
    {
        // do nothing
    }

    public function writeln($text = '')
    {
        // do nothing
    }

}