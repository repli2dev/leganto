<?php

/**
 * Test: Forms and Html.
 *
 * @author     David Grudl
 * @package    Nette\Forms
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



$form = new Form;
$form->addText('input', Html::el('b')->setText('Strong text.'));

Assert::match(<<<EOD
%A%
	<th><label for="frm-input"><b>Strong text.</b></label></th>
%A%
EOD
, (string) $form);
