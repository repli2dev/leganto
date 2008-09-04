<?php
class ModeratorTable extends Table {
	
	public function __construct($action) {
		parent::__construct();
		switch($action) {
			default:
			case "book":
				$res = Search::search("bookTitle",Page::get("searchWord"),Page::get("order"),Page::get("page"));
				$orderWriter = "writerName";
				$orderBook = "title";
				$orderRead = "countRead";
				$orderID = "id";
				switch(Page::get("order")) {
					case "":
						$orderBook .= " DESC";
						break;
					case $orderBook:
						$orderBook .= " DESC";
						break;
					case $orderWriter:
						$orderWriter .= " DESC";
						break;
					case $orderRead:
						$orderRead .= " DESC";
						break;
					case $orderID:
						$orderID .= " DESC";
						break;
				}
				$this->setHead(array(
					new A(Lng::ID,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderID",Lng::ORDER),
					new A(Lng::BOOK_TITLE,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderBook",Lng::ORDER),
					new A(Lng::WRITER,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderWriter",Lng::ORDER),
					new A(Lng::READ,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderRead",Lng::ORDER),
					new String(Lng::CHANGE)
				));
				while ($book = mysql_fetch_object($res)) {
					$this->addRow(array(
						new String($book->id),
						new A($book->title,"book.php?book=$book->id"),
						new A($book->writerName,"search.php?searchWord=".$book->writerName."&amp;column=writer"),
						new String($book->countRead),
						new A(Lng::CHANGE,"moderator.php?action=formBook&amp;book=".$book->id)
					));
				}
				break;
			case "writer":
				$res = Writer::search(Page::get("searchWord"),Page::get("order"),Page::get("page"));
				$orderID = "id";
				$orderWriter = "name";
				$orderCountBook = "countBook";
				switch(Page::get("order")) {
					case "":
					case $orderWriter:
						$orderWriter .= " DESC";
						break;
					case $orderCountBook:
						$orderCountBook .= " DESC";
						break;
					case $orderID:
						$orderID .= " DESC";
						break;
				}
				$this->setHead(array(
					new A(Lng::ID,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderID",Lng::ORDER),
					new A(Lng::WRITER,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderWriter",Lng::ORDER),
					new A(Lng::BOOK_COUNT,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderCountBook",Lng::ORDER),
					new String(Lng::CHANGE)
				));				
				while($writer = mysql_fetch_object($res)) {
					$this->addRow(array(
						new String($writer->id),
						new A($writer->name,"search.php?searchWord=".$writer->name."&amp;column=writer"),
						new String($writer->countBook),
						new A(Lng::CHANGE,"moderator.php?action=formWriter&amp;writer=".$writer->id)
					));
				}
				break;
			case "tag":
				$res = Tag::search(Page::get("searchWord"),Page::get("order"),Page::get("page"));
				$orderID = "id";
				$orderTag = "name";
				$orderTagged = "tagged";
				switch(Page::get("order")) {
					case "":
					case $orderTag:
						$orderTag .= " DESC";
						break;
					case $orderTagged:
						$orderTagged .= " DESC";
						break;
					case $orderID:
						$orderID .= " DESC";
						break;
				}
				$this->setHead(array(
					new A(Lng::ID,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderID",Lng::ORDER),
					new A(Lng::TAG,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderTag",Lng::ORDER),
					new A(Lng::TAGGED,"moderator.php?searchWord=".Page::get("searchWord")."&amp;searchColumn=".Page::get("searchColumn")."&amp;order=$orderTagged",Lng::ORDER),
					new String(Lng::CHANGE)
				));
				while ($tag = mysql_fetch_object($res)) {
					$this->addRow(array(
						new String($tag->id),
						new A($tag->name,"search.php?searchWord=".$tag->name."&amp;column=tag"),
						new String($tag->tagged),
						new A(Lng::CHANGE,"moderator.php?action=formTag&amp;tag=".$tag->id) 
					));
				}
				break;
		}
	}
}
?>