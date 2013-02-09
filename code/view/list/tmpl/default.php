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
<h1>LouCesWeb List</h1>

<p>
    <a class="btn btn-success" href="index.php?do=loucesweb">
        <i class="icon-plus"></i> New LouCesWeb
    </a>
</p>

<? if(! count($this->data)) : ?>

<div class="alert">
    No LouCesWebs found - let's <a href="index.php?do=loucesweb">create a new one</a>.
</div>

<? else: ?>

<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr>
        <th width="5%">Id</th>
        <th>A</th>
        <th>B</th>
        <th>C</th>
        <th width="5%">Action</th>
    </tr>
    </thead>

    <tbody>

    <? foreach($this->data as $item) : ?>

    <tr>
        <td><?= $item->loucesweb_id ?></td>
        <td><?= $item->a ? : '&nbsp;' ?></td>
        <td><?= $item->b ? : '&nbsp;' ?></td>
        <td><?= $item->c ? : '&nbsp;' ?></td>
        <td nowrap="nowrap">
            <a class="btn btn-mini" href="index.php?do=loucesweb&id=<?= $item->loucesweb_id ?>">
                <i class="icon-edit"></i>Edit
            </a>
            <a class="btn btn-mini" href="index.php?do=delete&id=<?= $item->loucesweb_id ?>">
                <i class="icon-remove"></i>Delete
            </a>
        </td>
    </tr>

    <? endforeach; ?>

    </tbody>
</table>

<? endif; ?>
