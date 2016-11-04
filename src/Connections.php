<?php
namespace PhpKit\ExtPDO;

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
    return !is_null (env ($prefix_ . 'DB_DRIVER'));
  }

  function get ($name = 'default')
  {
    $con = get ($this->connections, $name);
    if (!$con)
      $con = ($factory = get ($this->factories, $name))
        ? $this->connections[$name] = $factory ()
        : (
        ($newCon = Connection::getFromEnviroment ($name))
          ? $this->connections[$name] = $newCon
          : null
        );
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
