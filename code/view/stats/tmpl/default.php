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


?>
<h1>Statistiken</h1>
<h4 class="alert alert-block">Stark im aufbau <tt><a href="index.php?do=install">=;)</a></tt> </h4>


<div class="ui-widget" style="margin-left: 5px; width: 707px;">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>

	</div>
</div>
<div class="ui-widget" style="margin-left: 5px; width: 707px;">
	<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
	</div>
</div>


<?php if (isset($FileName))
{
	?>
	<form style="margin-top: 1em;" method="get" name="form" id="target">
		<div id="radioset_hours" class="ui-buttonset" style="margin-left: 5px; margin-top: 2px; float: left;">
			<?php if (is_array($hours)) foreach ($hours as $key => $hour)
			{
				$checked = ($hour == $scale_hours) ? ' checked="checked"' : '';
				echo "<input type=\"radio\" id=\"hours_{$key}\" value=\"{$hour}\" name=\"h\"{$checked}/><label for=\"hours_{$key}\">{$hour}h</label>";
			} ?>
		</div>
		<div id="radioset_weeks" class="ui-buttonset"
		     style="margin-left: 5px; margin-top: 2px; float: left;display:none;">
			<?php if (is_array($week)) foreach ($week as $key => $day)
			{
				$checked = ($day == $scale_week) ? ' checked="checked"' : '';
				echo "<input type=\"radio\" id=\"radio{$key}\" value=\"{$day}\" name=\"h\"{$checked}/><label for=\"radio{$key}\">{$day}h</label>";
			} ?>
		</div>
		<div id="radioset_months" class="ui-buttonset"
		     style="margin-left: 5px; margin-top: 2px; float: left;display:none;">
			<?php reset($months);if (is_array($months)) foreach ($months as $key => $month)
			{
				$checked = ($month == $scale_months) ? ' checked="checked"' : '';
				echo "<input type=\"radio\" id=\"month_{$key}\" value=\"{$key}\" name=\"m\"{$checked}/><label for=\"month_{$key}\">{$month}</label>";
			} ?>
		</div>
		<div id="radioset_years" class="ui-buttonset"
		     style="margin-left: 5px; margin-top: 2px; float: left;display:none;">
			<?php if (is_array($year)) foreach ($year as $key => $year)
			{
				$checked = ($year == $scale_years) ? ' checked="checked"' : '';
				echo "<input type=\"radio\" id=\"radio{$key}\" value=\"{$year}\" name=\"h\"{$checked}/><label for=\"radio{$key}\">{$year}h</label>";
			} ?>
		</div>
		<div style="margin-left: 5px; margin-top: 2px; float: left;">
			<select data-no_search="true" class="chzn-select" id="statSelect" name="stat">
				<?php if (is_array($stats)) foreach ($stats as $_key => $_stat)
				{
					$checked = ($_key == $selected_stat) ? ' selected="selected"' : '';
					echo "<option value=\"{$_key}\"{$checked}/>{$_stat}</option>";
				} ?>
			</select>
		</div>
		<div id="autocompleter" style="margin-left: 5px; margin-top: 2px;float: left;">
			<input id="autoName" name="name" style="z-index: 100; position: relative;" title="type &quot;name&quot;"
			       value="<?=$user_name?>"/>
		</div>
		<div style="margin-left: 5px; float: left;">
			<button type="submit" id="submitBtn">Anzeigen</button>
		</div>
		<div style="margin-left: 5px; margin-top: 2px; float: left;">
			<input type="checkbox" name="advanced" id="advanceBtn"
			       value="true"<?=($advanced) ? ' checked="checked"' : ''?>/><label for="advanceBtn">Erweitert</label>
		</div>
		<div style="margin-left: 5px; margin-top: 2px; float: left;">
			<input type="checkbox" name="c" id="clearcacheBtn" value="0"/><label for="clearcacheBtn">Cache
				l&ouml;schen</label>
		</div>
		<!--div style="margin-left: 5px; margin-top: 2px;">
			<select data-placeholder="alle Kontinente" style="width:250px;" multiple class="chzn-select" id="continentSelect" name="continents">
			  <option value=""></option>
			  <option>American Black Bear</option>
			  <option>Asiatic Black Bear</option>
			  <option>Brown Bear</option>
			  <option>Giant Panda</option>
			  <option selected>Sloth Bear</option>
			  <option disabled>Sun Bear</option>
			  <option selected>Polar Bear</option>
			  <option disabled>Spectacled Bear</option>
			</select>
		   </div-->
		<div style="clear:left;">
			<img src='<?=$FileName?>' alt='<?=$FileName?>' border='0'/>
		</div>
		<div id="tabs"
		     style="margin-left: 5px; margin-top: 2px; width: 701px;<?=($advanced) ? '' : ' display: none;'?>">
			<ul>
				<li><a href="#tabs-cities">St&auml;dte</a></li>
				<li><a href="#tabs-castles">Burgen</a></li>
				<li><a href="#tabs-palasts">Palaste</a></li>
			</ul>
			<div id="tabs-cities">
				<div id="accordion-cities" class="accordions">
					<?php if (!empty($_cities[0])) foreach ($_cities[0] as $c)
					{
						?>
						<div>
							<h3><a href="#"
							       style="background: url(<?=$c['mgraph']?>) 0% 50% no-repeat;"><?=$c['data']['name']?></a>
							</h3>

							<div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
								amet.<br/><img src='<?=$c['cgraph']?>' alt='<?=$c['cgraph']?>' border='0'/></div>
						</div>
					<?php
					}
					else
					{
						?>
						<p>keine St&auml;dte</p>
					<?php } ?>
				</div>
			</div>
			<div id="tabs-castles">
				<div id="accordion-castles" class="accordions">
					<?php if (!empty($_cities[1])) foreach ($_cities[1] as $c)
					{
						?>
						<div>
							<h3><a href="#"
							       style="background: url(<?=$c['mgraph']?>) 0% 50% no-repeat;"><?=$c['data']['name']?></a>
							</h3>

							<div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
								amet.<br/><img src='<?=$c['cgraph']?>' alt='<?=$c['cgraph']?>' border='0'/></div>
						</div>
					<?php
					}
					else
					{
						?>
						<p>keine Burgen</p>
					<?php } ?>
				</div>
			</div>
			<div id="tabs-palasts">
				<div id="accordion-palasts" class="accordions">
					<?php if (!empty($_cities[2])) foreach ($_cities[2] as $c)
					{
						?>
						<div>
							<h3><a href="#"
							       style="background: url(<?=$c['mgraph']?>) 0% 50% no-repeat;"><?=$c['data']['name']?></a>
							</h3>

							<div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
								amet.<br/><img src='<?=$c['cgraph']?>' alt='<?=$c['cgraph']?>' border='0'/></div>
						</div>
					<?php
					}
					else
					{
						?>
						<p>keine Palaste</p>
					<?php } ?>
				</div>
			</div>
		</div>
	</form>
<?php
}
else
{
	?>
	<p>Spieler nicht bekannt!</p>
<?php } ?>

