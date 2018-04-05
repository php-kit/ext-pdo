<?php
namespace PhpKit\ExtPDO\Interfaces;

use PhpKit\ExtPDO\Connection;


/**
 * A registry of database connections.
 *
 * <p>Connections are lazily instantiated as needed.
 */
interface ConnectionsInterface
{
  /**
   * Checks if a connection exists for the given name, or if one can be created.
   *
   * <p>If no connection with the given name exists or is registered, a check is made to the existence of environment
   * variables that define such a connection; these must be prefixed with the connection name and an underscore. If the
   * $name_DB_DRIVER variable does not exist, `false` is returned.
   *
   * @param string $name A key that identifies the connection on the set of available connections.
   *                     If not specified or the 'default' string, the default (main) connection is targeted.
   * @return bool TRUE if a connection with the given name is available.
   */
  function exists ($name = 'default');

  /**
   * Return a connection for the given name, if one exists or if it can be created.
   *
   * <p>If no connection with the given name exists or is registered, an attempt is made to automatically create a
   * connection from environment variables; these must be prefixed with the connection name and an underscore.
   * If the $name_DB_DRIVER variable does not exist, no connection is created and `null` is returned.
   *
   * @param string $name A key that identifies the connection on the set of available connections.
   *                     If not specified or the 'default' string, the default (main) connection is targeted.
   * @return Connection|null NULL if no connection with the given name is available.
   */
  function get ($name = 'default');

  /**
   * Registers a custom connection factory.
   *
   * @param string   $name    A key that identifies the connection on the set of available connections.
   *                          To target the default (main) connection, specify an empty string.
   * @param callable $factory A function that receives a connection class name and returns a new instance of
   *                          {@see \PhpKit\Connection}.
   * @return $this Self, for chaining declarations.
   */
  function register ($name, callable $factory);

  /**
   * Stores a connection instance on the container.
   *
   * @param string     $name A key that identifies the connection on the set of available connections.
   *                         To target the default (main) connection, specify an empty string.
   * @param Connection $con
   * @return $this Self, for chaining setters.
   */
  function set ($name, Connection $con);

  /**
   * Sets which class will be instantiated when creating connections from environment variables.
   *
   * @param string $class A fully qualified class name.
   * @return void
   * @return $this Self, for chaining.
   */
  function setConnectionClass ($class);
}
