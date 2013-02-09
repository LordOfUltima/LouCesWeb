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
 * LouCesWeb delete controller.
 *
 * @package     LouCesWeb
 * @subpackage  Controller
 */
class LcesControllerDelete extends JControllerBase
{
    /**
     * Execute the controller.
     *
     * @return  boolean  True if controller finished execution, false if the controller did not
     *                   finish execution. A controller might return false if some precondition for
     *                   the controller to run has not been satisfied.
     *
     * @since            12.1
     * @throws  LogicException
     * @throws  RuntimeException
     */
    public function execute()
    {
        $model = new LcesModelLoucesweb;

        /* @var LcesApplicationWeb $application */
        $application = JFactory::getApplication();
        $input = $application->input;

        if(false === $model->delete())
        {
            $application->addMessage('mother...', 'error');

            JLog::add('An error occured while deleting a record', JLog::ERROR);
        }
        else
        {
            $application->addMessage('Your LouCesWeb has been deleted', 'success');

            JLog::add('A record has been deleted');
        }

        $input->set('view', 'list');
    }
}
