<?php namespace Amu\Reemote\Gateway;

interface GatewayInterface {

    /**
     * Connect to the SSH server.
     *
     * @param  string  $username
     * @return void
     */
    public function connect($username);

    /**
     * Determine if the gateway is connected.
     *
     * @return bool
     */
    public function connected();

    /**
     * Run a command against the server (non-blocking).
     *
     * @param  string  $command
     * @return void
     */
    public function run($command, callable $callback = null);

    /**
     * Upload a local file to the server.
     *
     * @param  string  $local
     * @param  string  $remote
     * @return void
     */
    public function put($local, $remote);

    /**
     * Upload a string to to the given file on the server.
     *
     * @param  string  $remote
     * @param  string  $contents
     * @return void
     */
    public function putString($remote, $contents);

    /**
     * Get the exit status of the last command.
     *
     * @return int|bool
     */
    public function status();

}
