<?php
namespace PhpKit;
use PDO;

class ExtPDO extends PDO
{
  public $transactionDepth = 0;

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

}
