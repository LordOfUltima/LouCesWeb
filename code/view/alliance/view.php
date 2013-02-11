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
 * Html default view class.
 */
class LcesViewAllianceView extends JViewHtml
{
	protected $botPath = '';

	public function render()
	{
		$this->botPath = JFactory::getConfig()->get('louCestBotPath');

		/* @var $redis Redis */

		// include main files
		include_once($this->botPath . '/config.php');
		include_once($this->botPath . '/redis.php');
		include_once($this->botPath . '/lou.php');

		// Standard inclusions
		include_once($this->botPath . '/charts/pChart/pData.class');
		include_once($this->botPath . '/charts/pChart/pChart.class');
		include_once($this->botPath . '/charts/pChart/pCache.class');

		$alliId = 6;

		$this->alliance = $redis->hGetAll('alliance:' . $alliId . ':data');

		return parent::render();
	}

	protected function deBbCode($string)
	{
		return nl2br(
			str_replace(
				array('[u]', '[/u]','[b]', '[/b]', '[url]', '[/url]', '[spieler]', '[/spieler]'),
				'',
				$string
			)
		);
	}
}
