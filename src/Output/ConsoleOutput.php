<?php namespace Amu\Reemote\Output;

class ConsoleOutput implements OutputInterface {

    public function write($text)
    {
        $f = fopen('php://stdout', 'w');
        fwrite($f, $text);
        fclose($f);
    }

    public function writeln($text = '')
    {
        $this->write("$text\n", $pipe);
    }

}