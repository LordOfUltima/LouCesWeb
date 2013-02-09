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
 * LouCesWeb save controller.
 *
 * @package     LouCesWeb
 * @subpackage  Controller
 */
class LcesControllerSave extends JControllerBase
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

        try
        {
            $model->save();

            $application->addMessage('Your LouCesWeb has been saved', 'success');

            $input->set('view', 'list');

            JLog::add('A record has been saved');
        }
        catch(UnexpectedValueException $e)
        {
            $application->addMessage($e->getMessage(), 'error');

            $input->set('view', 'item');

            JLog::add($e->getMessage(), JLog::ERROR);
        }
    }
}
