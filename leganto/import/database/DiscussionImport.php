<?php
class DiscussionImport extends DatabaseTableImport
{

    protected function doImport() {
	$this->getDestination()->query("TRUNCATE TABLE [post]");
	$this->getDestination()->query("TRUNCATE TABLE [discussion]");
	$this->getDestination()->query("TRUNCATE TABLE [topic]");
	$this->getDestination()->query("TRUNCATE TABLE [discussable]");

	$this->discussable();
	$this->topics();
	$this->posts();
    }

    protected function discussable() {
	$this->getDestination()->insert("discussable", array(
	    "id_discussable"	=> 1,
	    "table"		=> "topic",
	    "column_id"		=> "id_topic",
	    "column_name"	=> "name",
	    "inserted"		=> new DateTime()
	))->execute();
	$this->getDestination()->insert("discussable", array(
	    "id_discussable"	=> 2,
	    "table"		=> "view_opinion",
	    "column_id"		=> "id_opinion",
	    "column_name"	=> "user_nick",
	    "inserted"		=> new DateTime()
	))->execute();
    }

    protected function posts() {
	// Discussion posts
	$posts	    = $this->getSource()->query("SELECT * FROM [reader_discussion] WHERE type = 'topic' OR type = 'discussion'")->fetchAll();
	$language   = $this->getDestination()->query("SELECT * FROM [language] WHERE [name] = 'czech'")->fetch();
	foreach($posts AS $post) {
	    $this->getDestination()->insert("post", array(
		"id_post"	=> $post["id"],
		"id_user"	=> $post["user"],
		"id_discussion"	=> ($post["type"] == 'topic' ? $post["id"] : $post["follow"]),
		"id_language"	=> $language["id_language"],
		"reply"		=> empty($post["parent"]) ? NULL : $post["parent"],
		"subject"	=> $post["title"],
		"content"	=> $post["text"],
		"inserted"	=> $post["date"]
	    ))->execute();
	}
	// Opinion comment posts
	$disNames	= $this->getDestination()->query("SELECT * FROM [book_title] GROUP BY [id_book] ORDER BY [inserted] DESC")->fetchPairs("id_book", "title");
	$opinionIds	= $this->getDestination()->query("SELECT * FROM [opinion] GROUP BY [id_book_title] ORDER BY [inserted] DESC")->fetchPairs("id_book_title", "id_opinion");
	$posts		= $this->getSource()->query("SELECT * FROM [reader_discussion] WHERE type = 'book'")->fetchAll();
	$discussions	= array();
	foreach($posts AS $post) {
	    if (empty($discussions[$post["follow"]])) {
		try {
		    $this->getDestination()->insert("discussion", array(
			"id_discussable"    => 2,
			"id_discussed"	=> $opinionIds[$post["follow"]],
			"name"		=> $disNames[$post["follow"]],
			"inserted"		=> $post["date"]
		    ))->execute();
		}
		catch(DibiDriverException $e) {
		    Debug::dump($post);
		    continue;
		}
		$discussions[$post["follow"]] = $this->getDestination()->query("SELECT * FROM [discussion] WHERE [id_discussable] = 2 AND [id_discussed] = %i", $opinionIds[$post["follow"]])->fetch();
	    }
	    $this->getDestination()->insert("post", array(
		"id_post"	=> $post["id"],
		"id_user"	=> $post["user"],
		"id_discussion"	=> $discussions[$post["follow"]]["id_discussion"],
		"id_language"	=> $language["id_language"],
		"reply"		=> empty($post["parent"]) ? NULL : $post["parent"],
		"subject"	=> $post["title"],
		"content"	=> $post["text"],
		"inserted"	=> $post["date"]
	    ))->execute();
	}
	echo $this->getDestination()->query("SELECT * FROM [post]")->count() . " POSTS IMPORTED\n";
    }

    protected function topics() {
	$topics = $this->getSource()->query("SELECT * FROM [reader_discussion] WHERE type = 'topic'")->fetchAll();
	foreach($topics AS $topic) {
	    $this->getDestination()->insert("topic", array(
		"id_user"   => $topic["user"],
		"id_topic"  => $topic["id"],
		"name"	    => $topic["title"],
		"inserted"  => $topic["date"]
	    ))->execute();
	    $this->getDestination()->insert("discussion", array(
		"id_discussion"	    => $topic["id"],
		"id_discussable"    => 1,
		"id_discussed"	    => $topic["id"],
		"name"	    => $topic["title"],
		"inserted"  => $topic["date"]
	    ))->execute();
	}
	echo $this->getDestination()->query("SELECT * FROM [topic]")->count() . " TOPICS IMPORTED\n";
    }

}
