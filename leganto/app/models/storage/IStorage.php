<?php
interface IStorage
{

	/** @return File */
	function getFile(IEntity $entity);

	/** @return File */
	function store(IEntity $entity, File $file);

}
