<?php
interface IStorage
{

	function getFile(IEntity $entity);

	function store(IEntity $entity, File $file);

}
