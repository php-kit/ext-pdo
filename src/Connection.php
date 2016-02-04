<?php
namespace PhpKit;

class Connection implements ConnectionInterface
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
  /** @var ExtPDO */
  private $pdo;

  static function getFromEnviroment ()
  {
    $cfg = new static;
    foreach (self::ENV_CONFIG_SETTINGS as $k => $p)
      $cfg->$p = env ($k);
    return $cfg;
  }

  function getPdo (array $options = null)
  {
    return $this->pdo ?: ($this->pdo = ExtPDO::create ($this->driver, $this->getProperties (), $options));
  }

  function getProperties ()
  {
    return get_object_vars ($this);
  }

  function isAvailable ()
  {
    return $this->driver && $this->driver !== 'none';
  }

  public function charset ($charset = null)
  {
    if (is_null ($charset)) return $this->charset;
    $this->charset = $charset;
    return $this;
  }

  public function collation ($collation = null)
  {
    if (is_null ($collation)) return $this->collation;
    $this->collation = $collation;
    return $this;
  }

  public function database ($database = null)
  {
    if (is_null ($database)) return $this->database;
    $this->database = $database;
    return $this;
  }

  public function driver ($driver = null)
  {
    if (is_null ($driver)) return $this->driver;
    $this->driver = $driver;
    return $this;
  }

  public function host ($host = null)
  {
    if (is_null ($host)) return $this->host;
    $this->host = $host;
    return $this;
  }

  public function password ($password = null)
  {
    if (is_null ($password)) return $this->password;
    $this->password = $password;
    return $this;
  }

  public function port ($port = null)
  {
    if (is_null ($port)) return $this->port;
    $this->port = $port;
    return $this;
  }

  public function prefix ($prefix = null)
  {
    if (is_null ($prefix)) return $this->prefix;
    $this->prefix = $prefix;
    return $this;
  }

  public function unixSocket ($unixSocket = null)
  {
    if (is_null ($unixSocket)) return $this->unixSocket;
    $this->unixSocket = $unixSocket;
    return $this;
  }

  public function username ($username = null)
  {
    if (is_null ($username)) return $this->username;
    $this->username = $username;
    return $this;
  }

}
