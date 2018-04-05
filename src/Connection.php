<?php
namespace PhpKit\ExtPDO;

use Electro\Traits\InspectionTrait;
use PhpKit\ExtPDO\Interfaces\ConnectionInterface;

/**
 * @see ConnectionInterface
 */
class Connection implements ConnectionInterface
{
  use InspectionTrait;

  static $ENV_CONFIG_SETTINGS = [
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

  /** @var ExtPDO */
  protected $pdo;

  private $charset;
  private $collation;
  private $database;
  private $driver;
  private $host;
  private $password;
  private $port;
  private $prefix;
  private $unixSocket;
  private $username;

  static function getFromEnviroment ($connectionName = 'default')
  {
    $cfg     = new static;
    $prefix_ = $connectionName && $connectionName != 'default' ? $connectionName . '_' : '';
    foreach (self::$ENV_CONFIG_SETTINGS as $k => $p) {
      $v = env ("$prefix_$k");
      if ($v !== '')
        $cfg->$p = $v;
    }
    if ($cfg->isAvailable())
      return $cfg;
    throw new \PDOException("Connection <kbd>$connectionName</kbd> is not defined");
  }

  function charset ($charset = null)
  {
    if (is_null ($charset)) return $this->charset;
    $this->charset = $charset;
    return $this;
  }

  function collation ($collation = null)
  {
    if (is_null ($collation)) return $this->collation;
    $this->collation = $collation;
    return $this;
  }

  function database ($database = null)
  {
    if (is_null ($database)) return $this->database;
    $this->database = $database;
    return $this;
  }

  function driver ($driver = null)
  {
    if (is_null ($driver)) return $this->driver;
    $this->driver = $driver;
    return $this;
  }

  function getPdo (array $options = null)
  {
    return $this->pdo ?: ($this->pdo = ExtPDO::create ($this->driver, $this->getProperties (), $options));
  }

  function getProperties ()
  {
    return get_object_vars ($this);
  }

  function host ($host = null)
  {
    if (is_null ($host)) return $this->host;
    $this->host = $host;
    return $this;
  }

  function isAvailable ()
  {
    return $this->driver && $this->driver !== 'none';
  }

  function password ($password = null)
  {
    if (is_null ($password)) return $this->password;
    $this->password = $password;
    return $this;
  }

  function port ($port = null)
  {
    if (is_null ($port)) return $this->port;
    $this->port = $port;
    return $this;
  }

  function prefix ($prefix = null)
  {
    if (is_null ($prefix)) return $this->prefix;
    $this->prefix = $prefix;
    return $this;
  }

  function unixSocket ($unixSocket = null)
  {
    if (is_null ($unixSocket)) return $this->unixSocket;
    $this->unixSocket = $unixSocket;
    return $this;
  }

  function username ($username = null)
  {
    if (is_null ($username)) return $this->username;
    $this->username = $username;
    return $this;
  }

}
