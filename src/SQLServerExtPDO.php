<?php
namespace Impactwave;

class SQLServerExtPDO extends ExtPDO
{
  /**
   * @param array $settings A configuration array with the following keys:<table cellspacing=0 cellpadding=0>
   *                        <tr>DB_DATABASE           <td>The database name.
   *                        <tr>DB_HOST               <td>The database server's host IP or domain name.
   *                        <tr>DB_PORT               <td>The database server's port (optional).
   *                        <tr>DB_USERNAME           <td>The username.
   *                        <tr>DB_PASSWORD           <td>The password.
   */
  function __construct (array $settings)
  {
    $dsn = "sqlsrv:Database={$settings['DB_DATABASE']};Server={$settings['DB_HOST']}";
    if (isset ($_ENV['DB_PORT']))
      $dsn .= ",{$settings['DB_PORT']}";
    parent::__construct ($dsn, $settings['DB_USERNAME'], $settings['DB_PASSWORD'], self::$OPTIONS);
  }
}
