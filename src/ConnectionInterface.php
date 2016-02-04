<?php
namespace PhpKit;

interface ConnectionInterface
{
  /**
   * Creates a connection instance whose properties are set from environment variables.
   *
   * > <p>**Tip:** this is more useful if used in conjunction with the `vlucas/phpdotenv` library.
   *
   * @return static
   */
  static function getFromEnviroment ();

  /**
   * Gets an extended PDO object initialized with the connection properties.
   *
   * @param array|null $options Entries on this array override the default PDO connection options.
   * @return MysqlExtPDO|PostgreSqlExtPDO|SqliteExtPDO|SqlserverExtPDO
   */
  function getPdo (array $options = null);

  /**
   * Gets all connection properties on an associative array.
   *
   * @return array
   */
  function getProperties ();

  /**
   * Checks if the connection properties have been set (at least, having a driver set).
   *
   * @return bool
   */
  function isAvailable ();

  /**
   * @param string $charset
   * @return $this|string
   */
  public function charset ($charset = null);

  /**
   * @param string $collation
   * @return $this|string
   */
  public function collation ($collation = null);

  /**
   * @param string $database
   * @return $this|string
   */
  public function database ($database = null);

  /**
   * @param string $driver
   * @return $this|string
   */
  public function driver ($driver = null);

  /**
   * @param string $host
   * @return $this|string
   */
  public function host ($host = null);

  /**
   * @param string $password
   * @return $this|string
   */
  public function password ($password = null);

  /**
   * @param int $port
   * @return $this|int
   */
  public function port ($port = null);

  /**
   * @param string $prefix
   * @return $this|string
   */
  public function prefix ($prefix = null);

  /**
   * @param string $unixSocket
   * @return $this|string
   */
  public function unixSocket ($unixSocket = null);

  /**
   * @param string $username
   * @return $this|string
   */
  public function username ($username = null);
}
