<?php
namespace PhpKit;

class Connection
{
  const ENV_CONFIG_SETTINGS = [
    'DB_CHARSET'     => 'charset',
    'DB_COLLATION'   => 'collation',
    'DB_DATABASE'    => 'database',
    'DB_DRIVER'      => 'driver',
    'DB_HOST'        => 'host',
    'DB_PASSWORD'    => 'password',
    'DB_PORT'        => 'port',
    'DB_PREFIX'      => 'prefix',
    'DB_UNIX_SOCKET' => 'unixSocket',
    'DB_USERNAME'    => 'username',
  ];

  public $charset;
  public $collation;
  public $database;
  public $driver;
  public $host;
  public $password;
  public $port;
  public $prefix;
  public $unixSocket;
  public $username;

  /**
   * Creates a connection instance whose properties are set from environment variables.
   *
   * > <p>**Tip:** this is more useful if used in conjunction with the `vlucas/phpdotenv` library.
   *
   * @return static
   */
  static function getFromEnviroment ()
  {
    $cfg = new static;
    foreach (self::ENV_CONFIG_SETTINGS as $k => $p)
      $cfg->$p = env ($k);
    return $cfg;
  }

  /**
   * Gets an extended PDO object initialized with the connection properties.
   *
   * @param array|null $options Entries on this array override the default PDO connection options.
   * @return MysqlExtPDO|PostgreSqlExtPDO|SqliteExtPDO|SqlserverExtPDO
   */
  function getPdo (array $options = null)
  {
    return ExtPDO::create ($this->driver, $this->getProperties (), $options);
  }

  /**
   * Gets all connection properties on an associative array.
   *
   * @return array
   */
  function getProperties ()
  {
    return get_object_vars ($this);
  }

  /**
   * Checks if the connection properties have been set (at least, having a driver set).
   *
   * @return bool
   */
  function isAvailable ()
  {
    return $this->driver && $this->driver !== 'none';
  }

}
