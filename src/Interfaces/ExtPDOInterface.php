<?php
namespace PhpKit\ExtPDO\Interfaces;

use \PDO;
use \PDOStatement;

/**
 * A low level interface to an SQL database that allows running queries and transactions on it.
 *
 * <p>It extends PHP's built-in PDO API to:
 *
 * 1. make it easier and simpler to run parameterized queries;
 * 2. provide partial support for nested transactions.
 *
 * <p>The actual class implementing this interface will vary depending on the database type.
 * <p>Currently, ExtPDO provides these classes:
 *
 * - {@see MysqlExtPDO}
 * - {@see PostgreSqlExtPDO}
 * - {@see SqliteExtPDO}
 * - {@see SqlserverExtPDO}
 *
 * <p>The class will initialize the connection with optimized settings that will be a better match to the most common
 * usage patterns than the PHP default settings are.
 *
 * <p>To create a new instance, either:
 *
 * - call {@see ExtPDO::create()}
 * - call {@see ConnectionInterface::getFromEnviroment()}
 * - call {@see ConnectionsInterface::get()}
 */
interface ExtPDOInterface
{
  /**
   * Begins a transaction if no nested transactions are active, or increments the nesting counter.
   * <p>
   * > **Note:** the real transaction will be committed only when all nested virtual transactions have also been
   * committed.
   */
  public function beginTransaction ();

  /**
   * Commits a transaction if no nested transactions are active, or decrements the nesting counter.
   */
  public function commit ();

  /**
   * Executes an SQL statement and returns the number of affected rows.
   * <p>
   * > **Note:** if you want to execute a SELECT statement, you will usually want to use
   * {@see ExtPDOInterface::select()} or {@see ExtPDOInterface::get()}.
   *
   * @param string     $statement
   * @param array|null $params
   * @return bool|int  <b>FALSE</b> on failure.
   */
  public function exec ($statement, $params = null);

  /**
   * Returns a single value from the first column of the first record of the result set of the given query.
   *
   * @param string $query
   * @param array  $params [optional] Extra arguments for the {@see ExtPDOInterface::query} call.
   * @return mixed|false <b>FALSE</b> on failure.
   */
  function get ($query, ...$params);

  /**
   * Returns the current transaction nesting depth.
   *
   * Value | Meaning
   * ------|---------
   * 0     | not in a transaction
   * 1...  | transaction depth, starting at 1
   *
   * @return int
   */
  function getTransactionDepth ();

  /**
   * Immediately rolls back the transaction and ignores further nested virtual roll backs.
   * <p>
   * > **Note:** when a nested transaction is active, all nested transactions are rolled back immediately. It is not
   * possible to just rollback the current nested transaction.
   * <p>You can still call `rollback()` once for each nested transaction, as all additional calls will be ignored.
   */
  public function rollBack ();

  /**
   * Similar to {@see ExtPDOInterface::query()} but it provides immediate binding to query parameters and automatic
   * statement preparation.
   *
   * @param string     $query
   * @param array|null $params
   * @param mixed      ...$fetchModeArgs
   * @return bool|\PDOStatement <b>false</b> on failure.
   */
  public function select ($query, $params = null, ...$fetchModeArgs);

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.1.0)<br/>
   * Prepares a statement for execution and returns a statement object
   *
   * @link http://php.net/manual/en/pdo.prepare.php
   * @param string $statement      <p>This must be a valid SQL statement for the target database server.
   * @param array  $driver_options [optional] <p>
   *                               This array holds one or more key=&gt;value pairs to set attribute values for the
   *                               <b>PDOStatement</b> object that this method returns.
   *                               <p>You would most commonly use this to set the <b>PDO::ATTR_CURSOR</b> value to
   *                               <b>PDO::CURSOR_SCROLL</b> to request a scrollable cursor.
   *                               <p>Some drivers have driver specific options that may be set at prepare-time.
   * @return PDOStatement|bool If the database server successfully prepares the statement,
   *                               <b>PDO::prepare</b> returns a <b>PDOStatement</b> object.
   *                               <p>If the database server cannot successfully prepare the statement,
   *                               <b>PDO::prepare</b> returns <b>FALSE</b> or emits
   *                               <b>PDOException</b> (depending on error handling).
   *                               <p>Emulated prepared statements does not communicate with the database server
   *                               so <b>PDO::prepare</b> does not check the statement.
   */
  public function prepare ($statement, $driver_options = null);

  /**
   * (PHP 5 &gt;= 5.3.3, Bundled pdo_pgsql, PHP 7)<br/>
   * Checks if inside a transaction
   *
   * @link http://php.net/manual/en/pdo.intransaction.php
   * @return bool <b>TRUE</b> if a transaction is currently active, and <b>FALSE</b> if not.
   */
  public function inTransaction ();

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.1.0)<br/>
   * Sets an attribute on the database handle.
   *
   * @link http://php.net/manual/en/pdo.setattribute.php
   * @param int   $attribute
   * @param mixed $value
   * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
   */
  public function setAttribute ($attribute, $value);

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.2.0)<br/>
   * Executes an SQL statement, returning a result set as a PDOStatement object.
   *
   * <p>**Note:** for most use cases, {@see ExtPDOInterface::select()} or {@see ExtPDOInterface::exec()} are more
   * convenient.
   *
   * <p>**Note:** the signature has an empty argument list for compatibility with PHP's built-in PDO class.
   *
   * ### Parameters:
   *
   * ####  string $statement
   * The SQL statement to prepare and execute.
   * <p>Data inside the query should be properly escaped.
   *
   * #### int $mode
   * The fetch mode must be one of the <b>PDO::FETCH_*</b> constants.
   * <p>Defaults to {@see PDO::ATTR_DEFAULT_FETCH_MODE}.
   *
   * #### mixed $arg3
   * The second and following parameters are the same as the parameters for {@see PDOStatement::setFetchMode}.
   *
   * #### array $ctorargs [optional]
   * Arguments of custom class constructor when the <i>mode</i> parameter is set to {@see PDO::FETCH_CLASS}.
   *
   * @return PDOStatement|bool <b>PDO::query</b> returns a PDOStatement object, or <b>FALSE</b> on failure.
   * @see  PDOStatement::setFetchMode For a full description of the second and following parameters.
   * @link http://php.net/manual/en/pdo.query.php
   */
  public function query ();

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.1.0)<br/>
   * Returns the ID of the last inserted row or sequence value
   *
   * @link http://php.net/manual/en/pdo.lastinsertid.php
   * @param string $name [optional] Name of the sequence object from which the ID should be returned.
   * @return string If a sequence name was not specified for the <i>name</i> parameter, <b>PDO::lastInsertId</b>
   *                     returns a string representing the row ID of the last row that was inserted into the database.
   *                     <p>If a sequence name was specified for the <i>name</i> parameter, <b>PDO::lastInsertId</b>
   *                     returns a string representing the last value retrieved from the specified sequence object.
   *                     <p>If the PDO driver does not support this capability, <b>PDO::lastInsertId</b> triggers an
   *                     IM001 SQLSTATE.
   */
  public function lastInsertId ($name = null);

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.2.0)<br/>
   * Returns the value of a database connection attribute.
   *
   * <p>To retrieve PDOStatement attributes, refer to {@see PDOStatement::getAttribute()}.
   * <p>Note that some database/driver combinations may not support all of the database connection attributes.
   *
   * @link http://php.net/manual/en/pdo.getattribute.php
   * @param int $attribute <p>
   *                       One of the PDO::ATTR_* constants.
   *                       <p>The constants that apply to database connections are as follows:
   *                       <p>{@see PDO::ATTR_AUTOCOMMIT}
   *                       <p>{@see PDO::ATTR_CASE}
   *                       <p>{@see PDO::ATTR_CLIENT_VERSION}
   *                       <p>{@see PDO::ATTR_CONNECTION_STATUS}
   *                       <p>{@see PDO::ATTR_DRIVER_NAME}
   *                       <p>{@see PDO::ATTR_ERRMODE}
   *                       <p>{@see PDO::ATTR_ORACLE_NULLS}
   *                       <p>{@see PDO::ATTR_PERSISTENT}
   *                       <p>{@see PDO::ATTR_PREFETCH}
   *                       <p>{@see PDO::ATTR_SERVER_INFO}
   *                       <p>{@see PDO::ATTR_SERVER_VERSION}
   *                       <p>{@see PDO::ATTR_TIMEOUT}
   * @return mixed A successful call returns the value of the requested PDO attribute.
   *                       An unsuccessful call returns null.
   */
  public function getAttribute ($attribute);

  /**
   * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.2.1)<br/>
   * Quotes a string for use in a query.
   *
   * @link http://php.net/manual/en/pdo.quote.php
   * @param string $string         The string to be quoted.
   * @param int    $parameter_type [optional] Provides a data type hint for drivers that have alternate quoting styles.
   * @return string a quoted string that is theoretically safe to pass into an SQL statement. Returns <b>FALSE</b>
   *                               if the driver does not support quoting in this way.
   */
  public function quote ($string, $parameter_type = PDO::PARAM_STR);
}
