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
 * LouCesWeb default controller.
 *
 * @package     LouCesWeb
 * @subpackage  Controller
 */
class LcesControllerUserquery extends JControllerBase
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
		$botPath = JFactory::getConfig()->get('louCestBotPath');

		$input = JFactory::getApplication()->input;

		/* @var $redis Redis */

		// include main files
		include_once($botPath . '/config.php');
		include_once($botPath . '/redis.php');

		$type = $input->get('type');
		$type = (in_array($type, array('user', 'alliance'))) ? $type : 'user';

		switch ($type)
		{
			case 'user':
				$users = $redis->hkeys("aliase");

				// filter by term
				$users = array_filter($users, function ($item)
				{
					return preg_match('/' . trim(JFactory::getApplication()->input->get('term')) . '/i', $item);
				});

				header('Content-type: application/json');
				echo trim(json_encode(array_values($users)));
				break;
			case 'alliance':
				$alliances = $redis->hkeys("alliances");

				// filter by term
				$alliances = array_filter($alliances, function ($item)
				{
					return preg_match('/' . trim(JFactory::getApplication()->input->get('term')) . '/i', $item);
				});

				header('Content-type: application/json');
				echo trim(json_encode(array_values($alliances)));
				break;
		}

		jexit();
	}
}
