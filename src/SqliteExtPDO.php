<?php
namespace PhpKit\ExtPDO;
use PDO;

/**
 * A PDO interface to SQLite databases.
 *
 * @see __construct
 */
class SqliteExtPDO extends ExtPDO
{
  /**
   * PDO options to be applied when connecting to the database.
   *
   * A map of PDO::ATTR_xxx constants to the corresponding values.
   * @var array
   */
  public $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_TIMEOUT            => 5,
  ];

  /**
   * Enable foreign keyd support on SQLite.
   *
   * Override to change the behaviour.
   */
  protected $enableForeignKeys = true;

  /**
   * @param array      $settings        A configuration array with the following keys:<p>
   *                                    <table cellspacing=0 cellpadding=0>
   *                                    <tr><kbd>database &nbsp; <td>The database filename. Use ':memory:' for an
   *                                    in-memory db.
   *                                    </table>
   * @param array|null $optionsOverride Entries on this array override the default PDO connection options.
   */
  function __construct (array $settings, array $optionsOverride = null)
  {
    $database = $settings['database'];
    if ($database != ':memory:') {
      if (!file_exists ($database))
        touch ($database);
    }
    if (isset($optionsOverride))
      foreach ($optionsOverride as $k => $v)
        $this->options[$k] = $v;
    parent::__construct ("sqlite:$database", '', '', $this->options);
    if ($this->enableForeignKeys)
      $this->exec ('PRAGMA foreign_keys = ON;');
  }
}
