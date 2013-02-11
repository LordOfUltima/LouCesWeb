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
<h2>Statistiken</h2>

<form method="post" action="index.php?do=stats" name="form" id="target" class="form-search">
	<div id="autocompleter" class="row well well-small">
		<label for="autoName">Spieler</label>
		<input id="autoName" placeholder="Spielername" class="search-query" name="name"
		       style="z-index: 100; position: relative;" title="type &quot;name&quot;"
		       value="<?= $this->userName ?>"/>
		<button type="submit" id="submitBtn">Anzeigen</button>
	</div>
	<?php if (!$this->userId && $this->userName) : ?>
		<div class="alert alert-error">Spieler nicht gefunden :(</div>
	<?php elseif ($this->userName): ?>
		<div id="radioset_hours" class="ui-buttonset pull-left">
			<?php foreach ($this->hours as $key => $hour) : ?>
				<?php $checked = ($hour == $scale_hours) ? ' checked="checked"' : ''; ?>
				<input type="radio" id="hours_<?= $key ?>" value="<?= $hour ?> " name="h"<?= $checked ?>/>
				<label for="hours_<?= $key ?>"><?= $hour ?>h</label>
			<?php endforeach; ?>
		</div>
		<!--
	<div id="radioset_weeks" class="ui-buttonset"
	     style="margin-left: 5px; margin-top: 2px; float: left;display:none;">
		<?php /*if (is_array($week)) :
			foreach ($week as $key => $day) :

			$checked = ($day == $scale_week) ? ' checked="checked"' : '';
			echo "<input type=\"radio\" id=\"radio{$key}\" value=\"{$day}\" name=\"h\"{$checked}/><label for=\"radio{$key}\">{$day}h</label>";
			endforeach;
		endif; ?>
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
		} */?>
	</div>
	-->
		<div class="pull-left">
			<select data-no_search="true" class="chzn-select span2" id="statSelect" name="stat">
				<?php foreach ($this->stats as $_key => $_stat)
				{
					$checked = ($_key == $selected_stat) ? ' selected="selected"' : '';
					echo "<option value=\"{$_key}\"{$checked}/>{$_stat}</option>";
				} ?>
			</select>
		</div>
		<div style="float: left;">
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

		<img src='tmp/<?= basename($this->fileName) ?>' alt='Statistics' border='0'/>

		<div id="tabs"
		     style="margin-left: 5px; margin-top: 2px; width: 701px;<?= ($this->advanced) ? '' : ' display: none;'?>">
			<ul>
				<li><a href="#tabs-cities">St&auml;dte</a></li>
				<li><a href="#tabs-castles">Burgen</a></li>
				<li><a href="#tabs-palasts">Paläste</a></li>
			</ul>
			<div id="tabs-cities">
				<div id="accordion-cities" class="accordions">
					<?php
					if (empty($this->cities[0])) :
						echo '<p>Keine Städte</p>';
					else :
						foreach ($this->cities[0] as $city) :
							echo $this->displayCity($city);
						endforeach;
					endif;
					?>
				</div>
			</div>
			<div id="tabs-castles">
				<div id="accordion-castles" class="accordions">
					<?php
					if (empty($this->cities[1])) :
						echo '<p>Keine Burgen</p>';
					else :
						foreach ($this->cities[1] as $city) :
							echo $this->displayCity($city);
						endforeach;
					endif;
					?>
				</div>
			</div>
			<div id="tabs-palasts">
				<div id="accordion-palasts" class="accordions">
					<?php
					if (empty($this->cities[2])) :
						echo '<p>Keine Paläste</p>';
					else :
						foreach ($this->cities[2] as $city) :
							echo $this->displayCity($city);
						endforeach;
					endif;
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</form>
