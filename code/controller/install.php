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
 * LouCesWeb install Controller.
 *
 * @package     LouCesWeb
 * @subpackage  Controller
 */
class LcesControllerInstall extends JControllerBase
{
    /**
     * Method to execute the controller.
     *
     * @throws RuntimeException
     *
     * @return bool|void
     */
    public function execute()
    {
        // Get the application database object.
        $db = JFactory::getDBO();

        // Get the installation database schema split into individual queries.
        switch($db->name)
        {
            case 'sqlite':
                $queries = JDatabaseDriver::splitSql(file_get_contents(dirname(JPATH_BASE)
                    .'/database/schema/sqlite/install.sql'));
                break;

            case 'mysql':
            case 'mysqli':
                $queries = JDatabaseDriver::splitSql(file_get_contents(dirname(JPATH_BASE)
                    .'/database/schema/mysql/install.sql'));
                break;

            default:
                throw new RuntimeException(sprintf('Database engine %s is not supported.', $db->name));
                break;
        }

        // Execute the installation schema queries.
        foreach($queries as $query)
        {
            if('' == trim($query))
                continue;

            $db->setQuery($query)->execute();
        }

        /* @var LcesApplicationWeb $application */
        $application = JFactory::getApplication();


        $application->addMessage('Your database has been created', 'success');

        JFactory::getApplication()->input->set('view', 'list');

        JLog::add('The database has been created');
    }
}
