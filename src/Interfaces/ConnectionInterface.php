<?php
namespace PhpKit\ExtPDO\Interfaces;

use PhpKit\ExtPDO\MysqlExtPDO;
use PhpKit\ExtPDO\PostgreSqlExtPDO;
use PhpKit\ExtPDO\SqliteExtPDO;
use PhpKit\ExtPDO\SqlserverExtPDO;

/**
 * Represents a database connection.
 *
 * <p>It allows settinga and getting configuration settings for the connection and it allows creating and/or retrieving
 * the associated {@see \PhpKit\ExtPDO\ExtPDO} instance.
 */
interface ConnectionInterface
{
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
   * Creates a connection instance whose properties are set from environment variables.
   *
   * > <p>**Tip:** this is more useful if used in conjunction with the `vlucas/phpdotenv` library.
   *
   * #### Examples
   * If `$connectionName = ''`, settings will be read from `DB_DRIVER, DB_DATABASE,` etc.
   *
   * If `$connectionName = 'myCon'`, settings will be read from `myCon_DB_DRIVER, myCon_DB_DATABASE,` etc.
   *
   * #### Supported environment variables
   * These are the recognized variables names (or suffixes) that will be used to setup connections.
   *
   *       DB_CHARSET
   *       DB_COLLATION
   *       DB_DATABASE
   *       DB_DRIVER
   *       DB_HOST
   *       DB_PASSWORD
   *       DB_PORT
   *       DB_PREFIX
   *       DB_UNIX_SOCKET
   *       DB_USERNAME
   *
   * @param string $connectionName Settings will be read from variables prefixed with this name and an underscore, or
   *                               with no prefix it this is not specified or if it's an empty string.
   * @return static
   */
  static function getFromEnviroment ($connectionName = '');

  /**
   * Gets an extended PDO object initialized with the connection properties.
   *
   * @param array|null $options Entries on this array override the default PDO connection options.
   * @return \PhpKit\ExtPDO\MysqlExtPDO|\PhpKit\ExtPDO\PostgreSqlExtPDO|SqliteExtPDO|\PhpKit\ExtPDO\SqlserverExtPDO
   */
  function getPdo (array $options = null);

  /**
   * Gets on an associative array all connection properties that are set.
   *
   * @return array
   */
  function getProperties ();

  /**
   * @param string $host
   * @return $this|string
   */
  public function host ($host = null);

  /**
   * Checks if the connection properties have been set (at least, having a driver set).
   *
   * @return bool
   */
  function isAvailable ();

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
