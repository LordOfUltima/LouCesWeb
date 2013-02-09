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
 * Html list view class.
 */
class LcesViewListView extends JViewHtml
{
    /**
     * @var array
     */
    protected $data;

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
        $this->data = $this->model->getData();

        return parent::render();
    }
}
