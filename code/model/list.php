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
 * LouCesWeb list model.
 *
 * @package     LouCesWeb
 * @subpackage  Model
 */
class LcesModelList extends JModelBase
{
    /**
     * Get the data.
     *
     * @return mixed
     */
    public function getData()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->from('#__loucesweb')
            ->select('*');

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
