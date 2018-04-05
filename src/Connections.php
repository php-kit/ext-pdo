<?php
namespace PhpKit\ExtPDO;

use PhpKit\ExtPDO\Interfaces\ConnectionsInterface;

/**
 * @see ConnectionsInterface
 */
class Connections implements Interfaces\ConnectionsInterface
{
  /**
   * @var string Determines which class will be instantiated when creating connections from environment variables.
   */
  protected $connectionClass = Connection::class;
  /**
   * @var Connection[]
   */
  protected $connections = [];
  /**
   * @var callable[]
   */
  protected $factories = [];

  function exists ($name = 'default')
  {
    if (isset ($this->connections[$name]) || isset ($this->factories[$name]))
      return true;
    $prefix_ = $name && $name != 'default' ? $name . '_' : '';
    return env ($prefix_ . 'DB_DRIVER') !== '';
  }

  function get ($name = 'default')
  {
    $con = get ($this->connections, $name);
    if (!$con) {
      if ($factory = get ($this->factories, $name))
        $con = $this->connections[$name] = $factory ();
      else {
        /** @var Connection $class */
        $class = $this->connectionClass;
        if ($con = $class::getFromEnviroment ($name))
          $this->connections[$name] = $con;
      }
    }
    return $con;
  }

  function register ($name, callable $factory)
  {
    $this->factories[$name] = $factory;
    return $this;
  }

  function set ($name, Connection $con)
  {
    $this->connections[$name] = $con;
    return $this;
  }

  function setConnectionClass ($class)
  {
    $this->connectionClass = $class;
  }

}
