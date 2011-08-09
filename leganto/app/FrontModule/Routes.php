<?php

namespace FrontModule;

use Nette\Application\Routers\Route;

/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
class Routes {

	public static function add($router, $prefix = "/") {

		// BACKWARD COMPABILITY
		// book.php?book=<book>
		// search.php?searchWord=<word>
		// search.php?searchWord=<word>&column=writer&order=writer
		// user.php?user=164
		// user.php?action=search
		// book.php?action=searchBook
		// about.php
		// discussion.php
		// discussion.php?action=readDis&follow=1222&type=discussion
		$router[] = new Route($prefix.'book.php?book=<book>', array(
			"module" => "web",
			'presenter' => 'Book',
			'action' => 'default',
		), Route::ONE_WAY);
// Cause problem
//		$router[] = new Route($prefix.'book.php?action=searchBook', array(
//			"module" => "web",
//			'presenter' => 'Book',
//			'action' => 'insert',
//		), Route::ONE_WAY);
		$router[] = new Route($prefix.'search.php?searchWord=<query>&column=writer&order=writer', array(
			"module" => "web",
			'presenter' => 'Search',
			'action' => 'author',
		), Route::ONE_WAY);
		$router[] = new Route($prefix.'search.php?searchWord=<query>', array(
			"module" => "web",
			'presenter' => 'Search',
			'action' => 'default',
		), Route::ONE_WAY);
		$router[] = new Route($prefix.'user.php?user=<user>', array(
			"module" => "web",
			'presenter' => 'User',
			'action' => 'default',
		), Route::ONE_WAY);
		$router[] = new Route($prefix.'helper.php?what=userIcon&id=<id>', array(
			"module" => "web",
			'presenter' => 'User',
			'action' => 'icon',
		), Route::ONE_WAY);
// Cause problem
//		$router[] = new Route($prefix.'user.php?action=search', array(
//			"module" => "web",
//			'presenter' => 'Search',
//			'action' => 'user',
//		), Route::ONE_WAY);
		$router[] = new Route($prefix.'about.php', array(
			"module" => "web",
			'presenter' => 'Help',
			'action' => 'default',
		), Route::ONE_WAY);
		$router[] = new Route($prefix.'discussion.php?action=readDis&follow=<discussed>&type=discussion', array(
			"module" => "web",
			'presenter' => 'Discussion',
			'action' => 'posts',
			'type' => \Leganto\DB\Post\Selector::TOPIC
		), Route::ONE_WAY);
		$router[] = new Route($prefix.'discussion.php', array(
			"module" => "web",
			'presenter' => 'Discussion',
			'action' => 'default',
		), Route::ONE_WAY);

		// The rest
		$router[] = new Route($prefix . "<presenter>/<action>", array(
			    "module" => "Front",
			    "presenter" => "Default",
			    "action" => "default"
			));
	}

}

