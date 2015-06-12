<?php
namespace Impactwave;
use PDO;

class MySQLExtPDO extends ExtPDO
{
  /**
   * @param array $settings A configuration array with the following keys:<table cellspacing=0 cellpadding=0>
   *                        <tr>DB_DATABASE           <td>The database name.
   *                        <tr>DB_HOST               <td>The database server's host IP or domain name.
   *                        <tr>DB_PORT               <td>The database server's port (optional).
   *                        <tr>DB_UNIX_SOCKET &nbsp; <td>The connection's UNIX socket (optional).
   *                        <tr>DB_CHARSET            <td>The database charset (optional), ex: 'utf8'.
   *                        <tr>DB_COLLATION          <td>The database collation (optional), ex: 'utf8_unicode_ci'.
   *                        <tr>DB_USERNAME           <td>The username.
   *                        <tr>DB_PASSWORD           <td>The password.
   */
  function __construct (array $settings)
  {
    $options = self::$OPTIONS;
    $dsn     = "mysql:host={$settings['DB_HOST']};dbname={$settings['DB_DATABASE']}";
    if (isset ($_ENV['DB_PORT']))
      $dsn .= ";port={$settings['DB_PORT']}";
    if (isset ($_ENV['DB_UNIX_SOCKET']))
      $dsn .= ";unix_socket={$settings['DB_UNIX_SOCKET']}";
    if (!empty($settings['DB_CHARSET'])) {
      $cmd = "SET NAMES '{$settings['DB_CHARSET']}'";
      if (!empty($settings['DB_COLLATION']))
        $cmd .= " COLLATE '{$settings['DB_COLLATION']}'";
      $options[PDO::MYSQL_ATTR_INIT_COMMAND] = $cmd;
    }
    $options[PDO::MYSQL_ATTR_FOUND_ROWS] = true;
    parent::__construct ($dsn, $settings['DB_USERNAME'], $settings['DB_PASSWORD'], self::$OPTIONS);
  }
}
