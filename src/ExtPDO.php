<?php
namespace PhpKit;

use PDO;

class ExtPDO extends PDO
{
  public $transactionDepth = 0;

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

  public function exec ($statement, $params = null)
  {
    if (!$params)
      return parent::exec ($statement);
    $st = $this->prepare ($statement);
    $r  = $st->execute ($params);
    return $r ? $st->rowCount () : false;
  }

  public function query ($statement, $params = null)
  {
    if (!$params)
      return parent::query ($statement);
    $st = $this->prepare ($statement);
    $r  = $st->execute ($params);
    return $r ? $st : false;
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
   * @param string     $query
   * @param array|null $params
   * @return mixed|false False if query result set is empty.
   */
  function get ($query, $params = null)
  {
    $st = $this->query ($query, $params);
    return $st->fetchColumn (0);
  }

}
