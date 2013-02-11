<?php
/**
 * User: elkuku
 * Date: 10.02.13
 * Time: 03:11
 */

?>

<h2><?= sprintf('Allianz: %1$s (%2$s)', $this->alliance['name'], $this->alliance['short']) ?></h2>

<div class="row row-fluid">
	<div class="span6 well alert">
		<?= $this->deBbCode($this->alliance['announce']) ?>
	</div>
	<div class="span6 well alert-info">
		<?= $this->deBbCode($this->alliance['desc']) ?>
	</div>
</div>

<p>Ja, und hier kommt jetzt noch 'was =;)</p>
<?php

//var_dump($this->alliance);
