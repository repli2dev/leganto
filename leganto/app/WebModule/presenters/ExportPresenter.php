<?php

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
class Web_ExportPresenter extends Web_BasePresenter
{
	private function stars($rating) {
		if ($rating == 0) {
			return '0 hvězdiček';
		}
		if ($rating == 1) {
			return '1 hvězdička';
		}
		if ($rating == 2) {
			return '2 hvězdičky';
		}
		if ($rating == 3) {
			return '3 hvězdičky';
		}
		if ($rating == 4) {
			return '4 hvězdičky';
		}
		if ($rating == 5) {
			return '5 hvězdiček';
		}
	}

	public function actionOpinions($user, $format = 'odt', $key = null)
	{
		$givenKey = $this->getParam("key");
		$expectedKey = Environment::getConfig("cron")->key;
		if ($givenKey != $expectedKey && $user != Environment::getUser()->getIdentity()->id) {
			$this->unauthorized();
		}
		// Prepare data
		$userEntity = Leganto::users()->getSelector()->find($user);
		$opinions = Leganto::opinions()->fetchAndCreateAll(
			Leganto::opinions()->getSelector()->findAllByUser($userEntity)->orderBy('inserted', 'ASC')
		);
		$bookTitleIds = [];
		foreach ($opinions as $opinion) {
			$bookTitleIds[] = $opinion->bookTitleId;
		}
		$authors = Leganto::authors()->getSelector()->findAllByBookTitleIds($bookTitleIds)->fetchAssoc('id_book_title,#');

		// Export data
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$properties = $phpWord->getDocInfo();
		$properties->setTitle('Názory uživatele: ' . $userEntity->nickname);

		$phpWord->setDefaultFontName('Verdana');
		$phpWord->setDefaultFontSize(12);

		$phpWord->addParagraphStyle('pStyle', array('align' => 'justify', 'spaceAfter' => 100));

		$titleFontStyle = array('name' => 'Verdana', 'size' => 30);
		$phpWord->addTitleStyle(1, $titleFontStyle);

		$titleFontStyle = array('name' => 'Verdana', 'size' => 20);
		$phpWord->addTitleStyle(2, $titleFontStyle);


		$section = $phpWord->addSection(array('pageNumberingStart' => 1));
		$section->addTitle(htmlspecialchars('Názory uživatele: ' . $userEntity->nickname), 1);
		foreach ($opinions as $opinion) {
			$section->addTitle(htmlspecialchars($opinion->bookTitle), 2);
			$output = '';
			if (isset($authors[$opinion->bookTitleId])) {
				$count = count($authors[$opinion->bookTitleId]);
				if ($count > 1) {
					$output .= 'Autoři: ';
				} else {
					$output .= 'Autor: ';
				}
				$temp = [];
				foreach ($authors[$opinion->bookTitleId] AS $author) {
					$temp[] = ($author->full_name) ? $author->full_name : $author->group_name;
				}
				if ($output) {
					$section->addText(htmlspecialchars($output . implode(', ', $temp)));
				}
			}
			$section->addText(htmlspecialchars('Vloženo dne: ' . DateTime53::createFromFormat('Y-m-d H:i:s', $opinion->inserted)->format('j. n. Y v H:i')));
			$section->addText(htmlspecialchars('Ohodnoceno: ' . Helpers::ratingHelper($opinion->rating) . ' (' . $this->stars($opinion->rating) . ')'));
			if ($opinion->content) {
				$section->addTextBreak();
				$temp2 = strip_tags(Helpers::texySafeHelperExport($opinion->content), '<p><em><strong><sup><sub><br>');
				$temp2 = str_replace("\n", ' ', $temp2);
				//$temp2 = str_replace("<br />", "<w:br />", $temp2);
				$html = str_replace('&', '&amp;', $temp2);
				\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
			}
		}
		// Result
		if ($format === 'docx') {
			$format = 'Word2007';
			$suffix = 'docx';
		} else {
			$format = 'ODText';
			$suffix = 'odt';
		}
		$path = WWW_DIR . '/../../exports/' . $user . '-opinions.' . $suffix;
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $format);
		$objWriter->save($path);
		$this->terminate(new DownloadResponse($path));
	}

	public function actionShelves($user, $format = 'odt', $key = null)
	{
		$givenKey		= $this->getParam("key");
		$expectedKey	= Environment::getConfig("cron")->key;
		if ($givenKey != $expectedKey && $user != Environment::getUser()->getIdentity()->id) {
			$this->unauthorized();
		}
		// Prepare data
		$userEntity = Leganto::users()->getSelector()->find($user);
		$shelves = Leganto::shelves()->fetchAndCreateAll(Leganto::shelves()->getSelector()->findByUser($userEntity));
		// Books in shelves
		$books = Leganto::books()->getSelector()->findAllInShelvesByUser($userEntity)->fetchAssoc("id_shelf,id_book_title");
		// Authors
		$bookTitleIds = [];
		foreach ($books as $shelfId => $bookTitles) {
			$bookTitleIds = array_merge($bookTitleIds, array_keys($bookTitles));
		}
		$authors = Leganto::authors()->getSelector()->findAllByBookTitleIds($bookTitleIds)->fetchAssoc('id_book_title,#');

		// Export data
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$properties = $phpWord->getDocInfo();
		$properties->setTitle('Poličky uživatele: ' . $userEntity->nickname);

		$phpWord->setDefaultFontName('Verdana');
		$phpWord->setDefaultFontSize(12);

		$phpWord->addParagraphStyle('pStyle', array('align' => 'justify', 'spaceAfter' => 100));

		$titleFontStyle = array('name' => 'Verdana', 'size' => 30);
		$phpWord->addTitleStyle(1, $titleFontStyle);

		$titleFontStyle = array('name' => 'Verdana', 'size' => 20);
		$phpWord->addTitleStyle(2, $titleFontStyle);

		$section = $phpWord->addSection(array('pageNumberingStart' => 1));
		$section->addTitle(htmlspecialchars('Poličky uživatele: ' . $userEntity->nickname), 1);
		foreach ($shelves as $shelf) {
			$section->addTitle(htmlspecialchars($shelf->name), 2);
			if (isset($books[$shelf->getId()])) {
				foreach ($books[$shelf->getId()] AS $bookTitleId => $bookRow) {
					$output = '';
					if (isset($authors[$bookTitleId])) {
						$count = count($authors[$bookTitleId]);
						if ($count > 1) {
							$output .= 'Autoři: ';
						} else {
							$output .= 'Autor: ';
						}
						$temp = [];
						foreach ($authors[$bookTitleId] AS $author) {
							$temp[] = ($author->full_name) ? $author->full_name : $author->group_name;
						}
						$output = '(' . $output . implode(', ', $temp) . ')';
					}
					$book = Leganto::books()->createEmpty()->loadDataFromArray($bookRow->toArray(), "Load");
					$section->addText(htmlspecialchars($book->title));
					$section->addText(htmlspecialchars($output));
					$section->addTextBreak();
				}
			} else {
				$section->addText(htmlspecialchars('Prázdná polička.'));
			}
		}
		// Result
		if ($format === 'docx') {
			$format = 'Word2007';
			$suffix = 'docx';
		} else {
			$format = 'ODText';
			$suffix = 'odt';
		}
		$path = WWW_DIR . '/../../exports/' . $user . '-shelves.' . $suffix;
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $format);
		$objWriter->save($path);
		$this->terminate(new DownloadResponse($path));
	}

}
