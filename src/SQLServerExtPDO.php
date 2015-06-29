<?php
namespace Impactwave;
use PDO;

class SQLServerExtPDO extends ExtPDO
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
   * @param array      $settings        A configuration array with the following keys:<table cellspacing=0
   *                                    cellpadding=0>
   *                                    <tr>DB_DATABASE           <td>The database name.
   *                                    <tr>DB_HOST               <td>The database server's host IP or domain name.
   *                                    <tr>DB_PORT               <td>The database server's port (optional).
   *                                    <tr>DB_USERNAME           <td>The username.
   *                                    <tr>DB_PASSWORD           <td>The password.
   *                                    </table>
   * @param array|null $optionsOverride Entries on this array override the default PDO connection options.
   */
  function __construct (array $settings, array $optionsOverride = null)
  {
    if (isset($optionsOverride))
      foreach ($optionsOverride as $k => $v)
        $this->options[$k] = $v;
    $dsn = "sqlsrv:Database={$settings['DB_DATABASE']};Server={$settings['DB_HOST']}";
    if (isset ($_ENV['DB_PORT']))
      $dsn .= ",{$settings['DB_PORT']}";
    parent::__construct ($dsn, $settings['DB_USERNAME'], $settings['DB_PASSWORD'], $this->options);
  }
}
