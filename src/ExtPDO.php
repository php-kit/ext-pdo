<?php
namespace Impactwave;
use PDO;

class ExtPDO extends PDO
{
  static $OPTIONS = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_TIMEOUT            => 5
  ];

  public $transactionDepth = 0;

  public function beginTransaction ()
  {
    if (++$this->transactionDepth == 1)
      parent::beginTransaction ();
  }

  public function commit ()
  {
    if (--$this->transactionDepth == 0)
      parent::commit ();
  }

  public function rollBack ()
  {
    if ($this->transactionDepth > 0) {
      $this->transactionDepth = 0;
      parent::rollBack ();
    }
  }

  function get ($query, $params = null)
  {
    $st = $this->query ($query, $params);
    if ($st)
      return $st->fetchColumn (0);
    return null;
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
