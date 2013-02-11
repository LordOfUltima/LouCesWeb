<?php
/**
 * @package    LouCesWeb
 * @subpackage Www
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 08-Feb-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

$do = JFactory::getApplication()->input->get('do');
$debug = JFactory::getApplication()->get('debug');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>LouCesWeb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="<?= JURI::root(true); ?>/template/css/bootstrap.css" rel="stylesheet">
    <link href="<?= JURI::root(true); ?>/template/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?= JURI::root(true); ?>/template/css/loucesweb.css" rel="stylesheet">

	<link href="<?= JURI::root(true); ?>/template/js/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
	<link href="<?= JURI::root(true); ?>/template/js/chosen/chosen.css" rel="stylesheet" />

	<script type="text/javascript" src="<?= JURI::root(true); ?>/template/js/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="<?= JURI::root(true); ?>/template/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="<?= JURI::root(true); ?>/template/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?= JURI::root(true); ?>/template/js/chosen/chosen.jquery.js"></script>

	<script src="<?= JURI::root(true); ?>/template/js/loucesweb.js" type="text/javascript"></script>

	<link rel="shortcut icon" href="<?= JURI::root(true); ?>/template/img/favicon.ico">
</head>

<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="#">LouCesWeb</a>

            <div class="nav-collapse">
                <ul class="nav">
                    <? $active = ('' == $do) ? ' active' : '' ?>
                    <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>">Home</a></li>

	                <? $active = ('stats' == $do) ? ' active' : '' ?>
	                <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>?do=stats">Statistiken</a></li>

                    <? $active = ('alliance' == $do) ? ' active' : '' ?>
	                <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>?do=alliance">Allianz</a></li>

	                <!--
	                <? $active = ('list' == $do) ? ' active' : '' ?>
                    <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>?do=list">LouCesWeb List</a></li>

                    <? $active = ('loucesweb' == $do) ? ' active' : '' ?>
                    <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>?do=loucesweb">New LouCesWeb</a></li>
                    -->

                    <? $active = ('log' == $do) ? ' active' : '' ?>
                    <li class="<?= $active ?>"><a href="<?= JURI::root(); ?>?do=log">Log</a></li>
                </ul>
            </div>
            <?php if($debug) : ?>
            <span class="label label-important">Debug</span>
            <?php endif; ?>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">
    <!--ApplicationMessage-->
    <!--ApplicationOutput-->

    <?php if($debug) : ?>
    <pre><!--ApplicationDebug--></pre>
    <?php endif; ?>
    <hr>

    <footer>
        <p>LouCesWeb is powered by <a href="http://joomla.org">Joomla!</a></p>
    </footer>
</div>
<!-- /container -->

</body>
</html>
