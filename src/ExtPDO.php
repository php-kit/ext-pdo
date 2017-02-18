<?php

namespace PhpKit\ExtPDO;

use PDO;

class ExtPDO extends PDO
{
  protected $transactionDepth = 0;

  /**
   * Creates an instance of an ExtPDO subclass that matches the given driver name.
   *
   * @param string     $driver          One of: mysql | pgsql | sqlite | sqlsrv
   * @param array      $settings        A configuration array that may have the following keys (depending on the
   *                                    driver):<p>
   *                                    <table cellspacing=0 cellpadding=0>
   *                                    <tr><kbd>database           <td>The database name.
   *                                    <tr><kbd>host               <td>The database server's host IP or domain name.
   *                                    <tr><kbd>port               <td>The database server's port (optional).
   *                                    <tr><kbd>unixSocket &nbsp;  <td>The connection's UNIX socket (optional).
   *                                    <tr><kbd>charset            <td>The database charset (optional), ex: 'utf8'.
   *                                    <tr><kbd>collation          <td>The database collation (optional),
   *                                    ex:'utf8_unicode_ci'.
   *                                    <tr><kbd>username           <td>The username.
   *                                    <tr><kbd>password           <td>The password.
   *                                    </table>
   * @param array|null $optionsOverride Entries on this array override the default PDO connection options.
   * @return MysqlExtPDO|PostgreSqlExtPDO|SqliteExtPDO|SqlserverExtPDO
   */
  static public function create ($driver, array $settings, array $optionsOverride = null)
  {
    switch ($driver) {
      case 'mysql':
        return new MysqlExtPDO ($settings, $optionsOverride);
      case 'pgsql':
        return new PostgreSqlExtPDO ($settings, $optionsOverride);
      case 'sqlite':
        return new SqliteExtPDO ($settings, $optionsOverride);
      case 'sqlsrv':
        return new SqlserverExtPDO ($settings, $optionsOverride);
    }
    throw new \RuntimeException ("Unsupported driver: $driver");
  }

  /**
   * Begins a transaction if one is not currently active and ignores further nested transaction requests.
   * <p>
   * > Note: Only when all nested virtual transactions are committed will the real transaction be committed.
   */
  public function beginTransaction ()
  {
    if (++$this->transactionDepth == 1)
      parent::beginTransaction ();
  }

  /**
   * Commits the real transaction only when all nested virtual transactions have been committed.
   */
  public function commit ()
  {
    if (--$this->transactionDepth == 0)
      parent::commit ();
  }

  /**
   * Executes an SQL statement and returns the number of affected rows.
   *
   * @param string     $statement
   * @param array|null $params
   * @return bool|int  FALSE on failure.
   */
  public function exec ($statement, $params = null)
  {
    if (!$params)
      return parent::exec ($statement);
    $st = $this->prepare ($statement);
    $r  = $st->execute ($params);
    return $r ? $st->rowCount () : false;
  }

  /**
   * Returns a single value from the first column of the first record of the result set of the given query.
   *
   * @param string $query
   * @param array  $params [optional] Extra arguments for the {@see PDO::query} call.
   * @return mixed|false FALSE on failure.
   */
  function get ($query, ...$params)
  {
    $st = $this->query ($query, ...$params);
    return $st ? $st->fetchColumn (0) : false;
  }

  /**
   * @return int
   */
  function getTransactionDepth ()
  {
    return $this->transactionDepth;
  }

  /**
   * Immediately rolls back the transaction and ignores further nested virtual roll backs.
   */
  public function rollBack ()
  {
    if ($this->transactionDepth > 0) {
      $this->transactionDepth = 0;
      parent::rollBack ();
    }
  }

  /**
   * Similar to {@see query} but supports immediate binding to query parameters.
   *
   * @param string     $query
   * @param array|null $params
   * @param mixed      ...$fetchModeArgs
   * @return bool|\PDOStatement
   */
  public function select ($query, $params = null, ...$fetchModeArgs)
  {
    if (!$params)
      return parent::query ($query, ...$fetchModeArgs);
    $st = $this->prepare ($query);
    if ($fetchModeArgs)
      $st->setFetchMode (...$fetchModeArgs);
    $r = $st->execute ($params);
    return $r ? $st : false;
  }

}
