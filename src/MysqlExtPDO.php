<?php
namespace PhpKit\ExtPDO;
use PDO;

/**
 * A PDO interface to MySQL databases.
 *
 * @see __construct
 */
class MysqlExtPDO extends ExtPDO
{
  /**
   * PDO options to be applied when connecting to the database.
   *
   * A map of PDO::ATTR_xxx constants to the corresponding values.
   *
   * @var array
   */
  public $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_TIMEOUT            => 5,
    PDO::MYSQL_ATTR_FOUND_ROWS   => true,  // FIX: compatibility problem; true = return how many rows update queries actually found/matched.
    PDO::ATTR_EMULATE_PREPARES   => false, // Make the mysqlnd driver return the proper field data types.
  ];

  /**
   * @var string Override this to set the default SQL Mode options for a connection.
   *             By default, it enables the use of double quotes for quoting identifiers, to make the query syntax more ANSI compliant.
   */
  protected $sqlMode = 'ANSI_QUOTES';

  /**
   * @param array      $settings        A configuration array with the following keys:<p>
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
   */
  function __construct (array $settings, array $optionsOverride = null)
  {
    $dsn = "mysql:host={$settings['host']};dbname={$settings['database']}";
    if (!empty ($settings['port']))
      $dsn .= ";port={$settings['port']}";
    if (!empty ($settings['unixSocket']))
      $dsn .= ";unix_socket={$settings['unixSocket']}";
    $cmd = [];
    if ($this->sqlMode)
      $cmd[] = "SESSION sql_mode='$this->sqlMode'";
    if (!empty($settings['charset'])) {
      $cmd2 = "NAMES '{$settings['charset']}'";
      if (!empty($settings['collation']))
        $cmd2 .= " COLLATE '{$settings['collation']}'";
      $cmd[] = $cmd2;
    }
    if ($cmd)
      $this->options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET ' . implode (',', $cmd);
    if (isset($optionsOverride))
      foreach ($optionsOverride as $k => $v)
        $this->options[$k] = $v;
    parent::__construct ($dsn, $settings['username'], $settings['password'], $this->options);
  }
}
