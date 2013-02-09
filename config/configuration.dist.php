<?php
/**
 * @package    LouCesWeb
 * @subpackage Config
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 08-Feb-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * Configuration class.
 */
class LcesConfig
{
    // Database credentials
    public $db_driver = 'sqlite';

    public $db_name = 'database.sdb';

    //public $db_host = 'localhost';
    //public $db_user = 'root';
    //public $db_pass = '';
    public $db_prefix = 'prfx_';

    public $debug = 1;
}
