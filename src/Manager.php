<?php namespace Amu\Reemote;

use Amu\Reemote\Output\NullOutput;
use Amu\Reemote\Output\ConsoleOutput;

class Manager {

    protected $config = array();

    protected $defaultConnection = 'default';

    protected $groups = array();

    public function __construct($defaultConfig)
    {
        $this->config[$this->defaultConnection] = $defaultConfig;
    }

    public function addConnection($name, $config)
    {
        $this->config[$name] = $config;
    }

    public function addGroup($name, $connectionNames)
    {
        $this->groups[$name] = $connectionNames;
    }

    /**
     * Get a remote connection instance.
     *
     * @param  string|array|mixed  $name
     * @return \Amu\Reemote\Connection\ConnectionInterface
     */
    public function into($name)
    {
        if (is_string($name) || is_array($name))
        {
            return $this->connection($name);
        }
        else
        {
            return $this->connection(func_get_args());
        }
    }

    /**
     * Get a remote connection instance.
     *
     * @param  string|array  $name
     * @return \Amu\Reemote\Connection\ConnectionInterface
     */
    public function connection($name = null)
    {
        if (is_array($name)) return $this->multiple($name);

        return $this->resolve($name ?: $this->getDefaultConnection());
    }

    /**
     * Get a connection group instance by name.
     *
     * @param  string  $name
     * @return \Amu\Reemote\Connection\ConnectionInterface
     */
    public function group($name)
    {
        return $this->connection($this->groups[$name]);
    }

    /**
     * Resolve a multiple connection instance.
     *
     * @param  array  $names
     * @return \Illuminate\Remote\MultiConnection
     */
    public function multiple(array $names)
    {
        return new MultiConnection(array_map(array($this, 'resolve'), $names));
    }

    /**
     * Resolve a remote connection instance.
     *
     * @param  string  $name
     * @return \Amu\Reemote\Connection\Connection
     */
    public function resolve($name)
    {
        return $this->makeConnection($name, $this->getConfig($name));
    }

    /**
     * Make a new connection instance.
     *
     * @param  string  $name
     * @param  array   $config
     * @return \Amu\Reemote\Connection\Connection
     */
    protected function makeConnection($name, array $config)
    {
        $this->setOutput($connection = new Connection(

            $name, $config['host'], $config['username'], $this->getAuth($config)

        ));

        return $connection;
    }

    /**
     * Set the output implementation on the connection.
     *
     * @param  \Amu\Reemote\Connection\Connection  $connection
     * @return void
     */
    protected function setOutput(Connection $connection)
    {
        $output = php_sapi_name() == 'cli' ? new ConsoleOutput : new NullOutput;

        $connection->setOutput($output);
    }

    /**
     * Format the appropriate authentication array payload.
     *
     * @param  array  $config
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getAuth(array $config)
    {
        if (isset($config['agent']) && $config['agent'] === true)
        {
            return array('agent' => true);
        }
        elseif (isset($config['key']) && trim($config['key']) != '')
        {
            return array('key' => $config['key'], 'keyphrase' => $config['keyphrase']);
        }
        elseif (isset($config['keytext']) && trim($config['keytext']) != '')
        {
            return array('keytext' => $config['keytext']);
        }
        elseif (isset($config['password']))
        {
            return array('password' => $config['password']);
        }

        throw new \InvalidArgumentException('Password / key is required.');
    }

    /**
     * Get the configuration for a remote server.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getConfig($name)
    {
        if ( isset($this->config[$name]) ) {
            return $this->config[$name];    
        }

        throw new \InvalidArgumentException("Remote connection [$name] not defined.");
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->defaultConnection;
    }

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->defaultConnection = $name;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->connection(), $method), $parameters);
    }

}

