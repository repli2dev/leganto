<?php

/**
 * Test: TemplateHelpers::bytes()
 *
 * @author     David Grudl
 * @package    Nette\Templates
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



Assert::same( "0 B", TemplateHelpers::bytes(0.1), "TemplateHelpers::bytes(0.1)" );


Assert::same( "-1.03 GB", TemplateHelpers::bytes(-1024 * 1024 * 1050), "TemplateHelpers::bytes(-1024 * 1024 * 1050)" );


Assert::same( "8881.78 PB", TemplateHelpers::bytes(1e19), "TemplateHelpers::bytes(1e19)" );
