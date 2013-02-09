<?php
/**
 * @package    LouCesWeb
 * @subpackage Code
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 08-Feb-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


/**
 * Html log view class.
 */
class LcesViewLogView extends JViewHtml
{
    /**
     * @var string
     */
    protected $log = '';

    /**
     * Method to render the view.
     *
     * @return  string  The rendered view.
     *
     * @since   12.1
     * @throws  RuntimeException
     */
    public function render()
    {
        $path = APP_PATH_DATA.'/log.php';

        $this->log = (file_exists($path))
            ? file_get_contents($path)
            : 'No log file found.';

        return parent::render();
    }
}
