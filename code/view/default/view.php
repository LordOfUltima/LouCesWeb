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
class LcesViewDefaultView extends JViewHtml
{
	protected $botPath = '';

	protected $userName = '';
	protected $userId = 0;

	protected $advanced = false;

	protected $cities = array();

	protected $hours = array();
	protected $stats = array();

	public function render()
	{
		$input  = JFactory::getApplication()->input;
		$tmpDir = dirname(APP_PATH_TEMPLATE) . '/tmp';

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

		####################

		$this->userName = $input->getCmd('name');
		$this->advanced = $input->getBool('advanced');
		$use_cache      = $input->getBool('c', true);

		$this->hours = array(24, 48, 72);
		$this->stats = array('hours' => 'Stunden',
			//'weeks'  => 'Woche',
			//'months' => 'Monat',
			//'years'  => 'Jahr'
		);
		$scale_hours = (in_array($input->getInt('h'), $this->hours)) ? $input->getInt('h') : 24;


		define('ALLTIME', -1);

		################

		$user_id = $redis->HGET('aliase', mb_strtoupper($this->userName));


		$this->userId = $user_id;


##########################

		// which world?
		$server_world = $redis->GET('server:world');

		// todo: fetch service with proper data
		$worlds = array('Welt 9'  => array('name' => 'W9', 'start' => 0),
		                'Welt 10' => array('name' => 'W10', 'start' => 1321952400),
		                'Welt 12' => array('name' => 'W12', 'start' => 1333008000),
		                'Welt 13' => array('name' => 'W13', 'start' => 1337839200),
		                'Welt 14' => array('name' => 'W14', 'start' => 1342771200),

			// @todo !!
		                'Welt 15' => array('name' => 'W15', 'start' => 1342771200)
		);

		//if (!$server_world) die('no world selected!');
		$server_world = 'Welt 15';

######################

		$world  = $worlds[$server_world]['name'];
		$wstart = $worlds[$server_world]['start'];


		// find definitions
//		$use_cache = (isset($_GET['c'])) ? (bool) $_GET['c'] : true;
		//$advanced      = (isset($_GET['advanced'])) ? (bool) $_GET['advanced'] : false;
		//$weeiks        = array(date("n") => 'diese Woche');
		//$months        = array(date("n") => 'dieser Monat');
		//$selected_stat = (in_array($_GET['stat'], $stats)) ? $_GET['stat'] : 'hours';
		$trans = array(
			'Monday'    => 'Montag',
			'Tuesday'   => 'Dienstag',
			'Wednesday' => 'Mittwoch',
			'Thursday'  => 'Donnerstag',
			'Friday'    => 'Freitag',
			'Saturday'  => 'Samstag',
			'Sunday'    => 'Sonntag',
			'Mon'       => 'Mo',
			'Tue'       => 'Di',
			'Wed'       => 'Mi',
			'Thu'       => 'Do',
			'Fri'       => 'Fr',
			'Sat'       => 'Sa',
			'Sun'       => 'So',
			'January'   => 'Januar',
			'February'  => 'Februar',
			'March'     => 'März',
			'May'       => 'Mai',
			'June'      => 'Juni',
			'July'      => 'Juli',
			'October'   => 'Oktober',
			'December'  => 'Dezember'
		);


		if ($user_id)
		{
			$_allstats = array_flip($redis->ZRANGEBYSCORE("user:{$user_id}:stats", "-inf", "+inf", array('withscores' => true)));
			/*
			  $_times   = array_keys($_allstats);
			  $_tend    = end($_times);
			  $_tstart  = reset($_times);
			  $_alltimes= array();
			  */
			// deathlog
			$_tend = end($_allstats);
			reset($_allstats);
			list(, , $_black_user_last,) = explode('|', $_tend);
			$_black_user_state = array();
			/*
			array_walk($_allstats, function (&$val, $key)
			{
				global $_black_user_state, $_black_user_last;
				//alliance_id|city_count|points|rank
				$val = explode('|', $val);
				if ($val[2] <> $_black_user_last) array_push($_black_user_state, $key);
			});
			*/

			foreach ($_allstats as $key => $val)
			{
				$val = explode('|', $val);
				if ($val[2] <> $_black_user_last) array_push($_black_user_state, $key);
			}

			sort($_black_user_state);
			$inactive = floor((time() - end($_black_user_state)) / 86400);

			if ($inactive == 0) $inactive_text = " - aktiv!";
			else if ($inactive >= 365) $inactive_text = " ... nicht berechnet!";
			else $inactive_text = " - {$inactive} Tage inaktiv!";

			/*
			  foreach($_times as $_time) {
				$_tdates[$_time] = getdate($_time);
				if (date('n', $_time) != date('n')) $months[date('n', $_time)] = strtr(strftime('%B', $_time), $trans);
				$_alltimes[$_tdates[$_time]['year']]['stat'] = $_allstats[$_time];
				$_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['stat'] = $_allstats[$_time];
				$_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['day'][$_tdates[$_time]['mday']]['stat'] = $_allstats[$_time];
				$_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['day'][$_tdates[$_time]['mday']]['hours'][$_tdates[$_time]['hours']] = $_allstats[$_time];
				$_alltimes[$_tdates[$_time]['year']]['day'][$_tdates[$_time]['yday']]['stat'] = $_allstats[$_time];
				$_alltimes[$_tdates[$_time]['year']]['day'][$_tdates[$_time]['yday']]['hours'][$_tdates[$_time]['hours']] = $_allstats[$_time];
			  }
			  #print_r($months);
			  */

			if ($scale_hours != ALLTIME)
			{
				$skip_scale = ($scale_hours != 24) ? 6 : 0;
				$sstart     = mktime(date("H") - $scale_hours, 0, 0, date("n"), date("j"), date("Y"));
				$start      = ($sstart < $wstart) ? $wstart : $sstart;
				$_dates     = array_reverse(range($start, mktime(date("H"), 0, 0, date("n"), date("j"), date("Y")), 60 * 60), true);

				// get user stats
				$latest      = '';
				$_user_stats = array();
				reset($_dates);
				$_i = 2;

				foreach ($_dates as $_start)
				{
					$_end   = $_start + (60 * 60);
					$_stats = array_flip($redis->ZRANGEBYSCORE("user:{$user_id}:stats", "({$_start}", "({$_end}", array('withscores' => true, 'limit' => array(0, 1))));

					if (empty($_stats) && !empty($latest))
					{
						$_user_stats[$_start] = $latest . '|' . $_i;
						$_i++;
					}
					else if (empty($_stats))
					{
						$_user_stats[$_start] = $latest;
					}
					else
					{
						$_user_stats[key($_stats)] = end($_stats);
						$latest                    = end($_stats);
						$_i                        = 2;
					}
				}
			}
			else
			{
				/*
				$_stats   = array_flip($redis->ZRANGEBYSCORE("user:{$user_id}:stats", "-inf", "+inf", array('withscores' => TRUE)));
				$_times   = array_keys($_stats);
				$_tend    = end($_times);
				$_tstart  = reset($_times);
				$_alltimes= array();
				echo date('d.m H:i', $_tstart).'<br/>';
				echo date('d.m H:i', $_tend).'<br/>';
				foreach($_times as $_time) {
				  $_tdates[$_time] = getdate($_time);
				  $_alltimes[$_tdates[$_time]['year']]['stat'] = $_stats[$_time];
				  $_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['stat'] = $_stats[$_time];
				  $_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['day'][$_tdates[$_time]['mday']]['stat'] = $_stats[$_time];
				  $_alltimes[$_tdates[$_time]['year']]['month'][$_tdates[$_time]['mon']]['day'][$_tdates[$_time]['mday']]['hours'][$_tdates[$_time]['hours']] = $_stats[$_time];
				  $_alltimes[$_tdates[$_time]['year']]['day'][$_tdates[$_time]['yday']]['stat'] = $_stats[$_time];
				  $_alltimes[$_tdates[$_time]['year']]['day'][$_tdates[$_time]['yday']]['hours'][$_tdates[$_time]['hours']] = $_stats[$_time];
				}
				echo "<pre>";print_r($_alltimes);exit;
				*/
			}

			$user     = $redis->HGETALL("user:{$user_id}:data");
			$alliance = $redis->HGETALL("alliance:{$user['alliance']}:data");

			$cities = ($this->advanced) ? $redis->SMEMBERS("user:{$user_id}:cities") : array();

			if (is_array($cities)) foreach ($cities as $cid => $pos)
			{
				$city_id = $redis->HGET("cities", $pos);
				// get city stats
				$latest      = array();
				$_city_stats = array();
				reset($_dates);

				foreach ($_dates as $_start)
				{
					$_end   = $_start + (60 * 60);
					$_stats = array_flip($redis->ZRANGEBYSCORE("city:{$city_id}:stats", "({$_start}", "({$_end}", array('withscores' => true, 'limit' => array(0, 1))));

					if (empty($_stats))
					{
						$_city_stats[$_start] = $latest;
					}
					else
					{
						$_city_stats[key($_stats)] = end($_stats);
						$latest                    = end($_stats);
					}
				}

				$latest     = array();
				$city_stats = array_reverse($_city_stats, true);

				foreach ($city_stats as $k => $v)
				{
					if (empty($city_stats[$k]) && !empty($latest)) $city_stats[$k] = $latest;

					$latest = $city_stats[$k];
				}

				$cities[$cid] = array('data' => $redis->HGETALL("city:{$city_id}:data"), 'stats' => $city_stats);
				// mini graph
				// Dataset definition
				$mpoints     = array();
				$mtype       = array();
				$mname       = array();
				$mally       = array();
				$mowner      = array();
				$mtime       = array();
				$mlast_owner = 0;
				$mlast_ally  = 0;
				$mlast_name  = '';
				$mlast_type  = 0;
				$mdata_count = 0;
				//array_walk($cities[$cid]['stats'], function (&$val, $key)

				foreach ($cities[$cid]['stats'] as $key => $val)
				{
					//global $skip_scale, $mlast_ally, $mally, $mlast_name, $mname, $mlast_owner, $mowner, $mpoints, $mtype, $mlast_type, $mtime, $mdata_count, $trans, $redis;
					//name|state|water|alliance_id|user_id|points
					$_time = getdate($key);

					if (($_time['hours'] == 0 || $_time['hours'] == 12) OR ($skip_scale > 0))
					{
						$abscise_key = $mtime[] = date('d.m H:i', $key);
					}
					else
					{
						$abscise_key = $mtime[] = strtr(strftime('%a %H:%M', $key), $trans);
					}

					$val = explode('|', $val);

					if ($val[0] != $mlast_name && $mdata_count > 0) $mname[$abscise_key] = array($val[0], $mlast_name);

					$mlast_name = $val[0];

					if ($val[1] != $mlast_type && $mdata_count > 0) $mtype[$abscise_key] = array(array('s' => $val[1], 'w' => $val[2]), array('s' => $mlast_type, 'w' => $val[2]));

					$mlast_type = $val[1];

					if ($val[3] != $mlast_ally && $mdata_count > 0) $mally[$abscise_key] = array($val[3], $mlast_ally);

					$mlast_ally = $val[3];

					if ($val[4] != $mlast_owner && $mdata_count > 0) $mowner[$abscise_key] = array($val[4], $mlast_owner);

					$mlast_owner = $val[4];
					$mpoints[]   = $val[5];
					$mdata_count++;
				}
				//	);

				$mperformance = number_format(round((($mpoints[count($mpoints) - 1] / $mpoints[0]) - 1) * 100, 2), 2, ',', '');
				// mFile
				$mFileName = './tmp/' . md5($city_id) . ".png";
				// mgraph
				$mDataSet = new pData;
				$mDataSet->AddPoint($mpoints, "points");
				$mDataSet->AddAllSeries();
				$mDataSet->SetAbsciseLabelSerie();
				$mDataSet->SetSerieName("Punkte", "points");
				// Cache definition
				$mCache = new pCache($this->botPath . '/charts/Cache/');

				if (($use_cache && !$mCache->GetFromCache(md5($city_id), $mDataSet->GetData(), $mFileName)) or !$use_cache)
				{
					// Initialise the graph
					$mTest = new pChart(100, 30);
					$mTest->setFontProperties('Fonts/tahoma.ttf', 8);
					#$mTest->drawFilledRoundedRectangle(2,2,98,28,2,255,255,255);
					#$mTest->drawRoundedRectangle(2,2,98,28,2,163,203,167);
					$mTest->setGraphArea(5, 5, 95, 25);
					$mTest->drawGraphArea(255, 255, 255);
					$mTest->drawScale($mDataSet->GetData(), $mDataSet->GetDataDescription(), SCALE_DIFF, 255, 255, 255, false, 0, 2, false, true, false);
					// Draw the line graph
					$mTest->drawLineGraph($mDataSet->GetData(), $mDataSet->GetDataDescription());
					// Finish the graph
					$mCache->WriteToCache(md5($city_id), $mDataSet->GetData(), $mTest);
					$mTest->Render($mFileName);
				}

				$cities[$cid]['mgraph'] = $mFileName;
				// Dataset definition
				// cFile
				$cFileName = './tmp/' . md5($cities[$cid]['data']['pos']) . '.png';
				// cgraph
				$cDataSet = new pData;
				$cDataSet->AddPoint($mpoints, "points");
				$cDataSet->AddPoint($mtime, "time");
				$cDataSet->AddSerie("points");
				$cDataSet->SetAbsciseLabelSerie();
				$cDataSet->SetSerieName("Punkte", "points");
				$cDataSet->SetYAxisName("Punkte");
				$cDataSet->SetAbsciseLabelSerie("time");
				//$cDataSet->SetXAxisFormat("date");
				// Cache definition
				$cCache = new pCache($this->botPath . '/charts/Cache/');

				if (($use_cache && !$cCache->GetFromCache(md5($cities[$cid]['data']['pos']), $cDataSet->GetData(), $cFileName)) OR !$use_cache)
				{
					// Initialise the graph
					$cTest = new pChart(615, 230);
					$cTest->setDateFormat('d.m H:i');
					$cTest->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 8);
					$cTest->setGraphArea(60, 30, 550, 150);
					$cTest->drawFilledRoundedRectangle(7, 7, 608, 223, 5, 240, 240, 240);
					$cTest->drawRoundedRectangle(5, 5, 610, 225, 5, 163, 203, 167);
					$cTest->drawGraphArea(255, 255, 255, true);
					$cTest->drawGraphAreaGradient(163, 203, 167, 50);
					$cTest->drawScale($cDataSet->GetData(), $cDataSet->GetDataDescription(), SCALE_DIFF, 150, 150, 150, true, 75, 0, false, $skip_scale);
					$cTest->drawGrid(4, true, 230, 230, 230, 40);
					// Draw the graph
					$cTest->drawFilledCubicCurve($cDataSet->GetData(), $cDataSet->GetDataDescription(), .1, 30);

					if ($scale_hours <= 48) $cTest->drawPlotGraph($cDataSet->GetData(), $cDataSet->GetDataDescription(), 2, 1, 255, 255, 255);

					// Draw labels
					if (!empty($mname)) foreach ($mname as $k => $v)
					{
						$cTest->setLabel($cDataSet->GetData(), $cDataSet->GetDataDescription(), "points", $k, $v[1], 239, 233, 195);
					}

					if (!empty($mtype)) foreach ($mtype as $k => $v)
					{
						$cTest->setLabel($cDataSet->GetData(), $cDataSet->GetDataDescription(), "points", $k, 'Status: ' . LoU::prepare_city_type($v[0]), 221, 230, 174);
					}

					if (!empty($mowner)) foreach ($mowner as $k => $v)
					{
						$_un = $redis->HGET("user:{$v[1]}:data", 'name');
						if ($_un) $cTest->setLabel($cDataSet->GetData(), $cDataSet->GetDataDescription(), "points", $k, 'Übernahme: ' . $_un, 239, 233, 195);
					}

					$cTest->clearShadow();

					// Finish the graph
					$cTest->drawLegend(75, 35, $cDataSet->GetDataDescription(), 236, 238, 240, 52, 58, 82);
					$cTest->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 10);
					$cTest->drawTitle(60, 22, $cities[$cid]['data']['name'] . ' - ' . $scale_hours . 'h' . ' Performance: ' . $mperformance . '%', 50, 50, 50, 585);

					// Render the graph
					$cCache->WriteToCache(md5($cities[$cid]['data']['pos']), $cDataSet->GetData(), $cTest);
					$cTest->Render($cFileName);
				}

				$cities[$cid]['cgraph'] = $cFileName;
			}

			$this->cities[0] = array();
			$this->cities[1] = array();
			$this->cities[2] = array();
			$city_order      = 'name';

			if (!empty($cities)) foreach ($cities as $city)
			{
				$this->cities[$city['data']['state']][$city['data'][$city_order]] = $city;
			}

			sort($this->cities[0]);
			sort($this->cities[1]);
			sort($this->cities[2]);
			$points     = array();
			$ccities    = array();
			$rank       = array();
			$ally       = array();
			$time       = array();
			$last_ally  = 0;
			$city_count = 0;
			$data_count = 0;

			$_i         = 2;
			$latest     = '';
			$user_stats = array_reverse($_user_stats, true);

			foreach ($user_stats as $k => $v)
			{
				if (empty($user_stats[$k]) && !empty($latest))
				{
					$user_stats[$k] = $latest . '|' . $_i;
					$_i++;
				}
				else $_i = 2;

				$latest = $user_stats[$k];
			}

			$last_points = 0;
			$last_rank   = 0;

			//array_walk($user_stats, function (&$val, $key)
			foreach ($user_stats as $key => $val)
			{
				//global $skip_scale, $city_count, $last_ally, $ally, $ccities, $points, $rank, $time, $data_count, $trans, $last_points, $last_rank;
				//alliance_id|city_count|points|rank|periode
				$_time = getdate($key);
				if (($_time['hours'] == 0 || $_time['hours'] == 12) OR ($skip_scale > 0))
				{
					$abscise_key = $time[] = date('d.m H:i', $key);
				}
				else
				{
					$abscise_key = $time[] = strtr(strftime('%a %H:%M', $key), $trans);
				}

				$val = explode('|', $val);

				if ($val[0] != $last_ally && $data_count > 0) $ally[$abscise_key] = $val[0];

				$last_ally = $val[0];

				if ($val[1] != $city_count && $data_count > 0) $ccities[$abscise_key] = $val[1];

				$city_count = $val[1];

				if (!$val[4])
				{
					$last_points = $points[] = $val[2];
					$last_rank   = $rank[] = $val[3];
				}
				else
				{
					$_points     = $val[2] - $last_points;
					$_rank       = $val[3] - $last_rank;
					$last_points = $points[] = $last_points + round($_points / $val[4]);
					$last_rank   = $rank[] = $last_rank + round($_rank / $val[4]);
				}
				$data_count++;
			}
			//);
			$performance = number_format(round((($points[count($points) - 1] / $points[0]) - 1) * 100, 2), 2, ',', '');
			// Dataset definition
			// File
			$FileName = $tmpDir . '/' . md5($user['name']) . '.png';
			// Graph
			$DataSet = new pData;
			$DataSet->AddPoint($points, "points");
			$DataSet->AddPoint($rank, "rank");
			$DataSet->AddPoint($time, "time");
			$DataSet->AddSerie("points");

			$DataSet->SetAbsciseLabelSerie();
			$DataSet->SetSerieName("Punkte", "points");
			$DataSet->SetSerieName("Rang", "rank");
			$DataSet->SetYAxisName("Punkte");
			$DataSet->SetAbsciseLabelSerie("time");
			//$DataSet->SetXAxisFormat("date");
			// Cache definition
			$Cache = new pCache($this->botPath . '/charts/Cache/');

			if (($use_cache && !$Cache->GetFromCache(md5($user['name']), $DataSet->GetData(), $FileName)) OR !$use_cache)
			{
				// Initialise the graph
				$Test = new pChart(715, 230);
				$Test->setDateFormat('d.m H:i');
				$Test->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 8);
				$Test->setGraphArea(60, 30, 650, 150);
				$Test->drawFilledRoundedRectangle(7, 7, 708, 223, 5, 240, 240, 240);
				$Test->drawRoundedRectangle(5, 5, 710, 225, 5, 163, 203, 167);
				$Test->drawGraphArea(255, 255, 255, true);
				$Test->drawGraphAreaGradient(163, 203, 167, 50);
				$Test->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_DIFF, 150, 150, 150, true, 75, 0, false, $skip_scale);
				$Test->drawGrid(4, true, 230, 230, 230, 40);
				// Draw the graph
				$Test->drawFilledCubicCurve($DataSet->GetData(), $DataSet->GetDataDescription(), .1, 30);

				if ($scale_hours <= 48) $Test->drawPlotGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 2, 1, 255, 255, 255);

				// Draw labels
				if (!empty($ccities)) foreach ($ccities as $k => $v)
				{
					$Test->setLabel($DataSet->GetData(), $DataSet->GetDataDescription(), "points", $k, "Städte: {$v}", 239, 233, 195);
				}

				$Test->clearShadow();
				// Clear the scale
				$Test->clearScale();

				// Draw the 2nd graph
				$DataSet->RemoveSerie("points");
				$DataSet->AddSerie("rank");
				$DataSet->SetYAxisName("Rang");
				$Test->drawRightScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_DIFF, 150, 150, 150, true, 75, 0, false, $skip_scale, true);
				// Draw the 0 line
				$Test->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 6);
				$Test->drawTreshold(0, 143, 55, 72, true, true);
				// Draw the Line graph
				$Test->drawFilledCubicCurve($DataSet->GetData(), $DataSet->GetDataDescription(), .1, 20);

				if ($scale_hours <= 48) $Test->drawPlotGraph($DataSet->GetData(), $DataSet->GetDataDescription(), 2, 1, 255, 255, 255);

				// Draw Labels
				$Test->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 8);

				if (!empty($ally)) foreach ($ally as $k => $v)
				{
					if ($v != 0)
					{
						$_alliance = $redis->HGETALL("alliance:{$v}:data");
						$Test->setLabel($DataSet->GetData(), $DataSet->GetDataDescription(), "rank", $k, "Ally: {$_alliance['name']}", 221, 230, 174);
					}
					else
					{
						$Test->setLabel($DataSet->GetData(), $DataSet->GetDataDescription(), "rank", $k, "keine Alliance", 221, 230, 174);
					}
				}

				// Finish the graph
				$Test->drawLegend(75, 35, $DataSet->GetDataDescription(), 236, 238, 240, 52, 58, 82);
				$Test->setFontProperties($this->botPath . '/charts/Fonts/tahoma.ttf', 10);
				$Test->drawTitle(60, 22, $user['name'] . ((!empty($alliance['name'])) ? ' [' . $alliance['name'] . ']' : '') . ' - ' . $scale_hours . 'h' . ' Performance: ' . $performance . '%' . $inactive_text, 50, 50, 50, 585);

				// Render the graph
				$Cache->WriteToCache(md5($user['name']), $DataSet->GetData(), $Test);
				$Test->Render($FileName);
			}
		}

		$this->fileName = $FileName;

		return parent::render();
	}

	protected function displayCity($city)
	{
		$html = array();

		$name = $city['data']['name'];
		$name .= ($city['data']['water']) ? '&nbsp;&nbsp;<b style="color: blue;">' . $city['data']['category'] . '</b>' : '';

		$html[] = '<div>';
		$html[] = '   <h3>';
		$html[] = '      <a href="#" style="background: url(' . $city['mgraph'] . ') 0% 50% no-repeat;">' . $name . '</a>';
		$html[] = '   </h3>';

		$html[] = '   <div>';
		$html[] = $this->getCityInfo($city) . '<br/>';
		$html[] = '      <img src="' . $city['cgraph'] . '" alt="' . $city['cgraph'] . '" border="0" />';
		$html[] = '   </div>';
		$html[] = '</div>';

		return implode("\n", $html);

	}

	protected function getCityInfo($city)
	{
		$info = '';

		$data = $city['data'];
		$info .= sprintf(' (%d:%d)', $data['x-coord'], $data['y-coord']);
		$info .= ' ' . $data['points'] . ' Punkte';

		return $info;
	}
}
