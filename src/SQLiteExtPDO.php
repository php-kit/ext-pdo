<?php
namespace Impactwave;

class SQLiteExtPDO extends ExtPDO
{
  function __construct ($database)
  {
    if (!file_exists ($database))
      touch ($database);
    parent::__construct ("sqlite:$database", '', '', self::$OPTIONS);
    $this->exec ('PRAGMA foreign_keys = ON;');
  }
}
