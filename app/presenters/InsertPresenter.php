<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class InsertPresenter extends BasePresenter
{

	public function actionInsert($type) {
		if (!$this->getRequest()->isPost()) {
			$this->code(403);
		}
		// Load data from the POST array
		$data = $this->getRequest()->getPost();
		// Try to find the entity factory by given type
		$factory = SimpleEntityFactory::createEntityFactory($type);
		// Create an entity
		$entity = $factory->createEmpty();
		$entity->loadDataFromArray($data);
		// Insert the entity
		$insertedId = $factory->getInserter()->insert($entity);
		// Return '200 OK'
		$this->code(200, $insertedId);
	}

	// PROTECTED METHODS

	protected function startUp() {
		// Check if the method is POST method
		parent::startUp();
		// TODO:	HTTP autantizace pravdepodobne nebude fungovat,
		//			proto bude potreba kontrolovat pristup jinak
	}

}
