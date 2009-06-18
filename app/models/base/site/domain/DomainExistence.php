<?php
/**
 * It checks existence of domain based on its uri.
 *
 * @author Jan Papousek
 */
class DomainExistence implements IExistence
{

	private $exists;

	/**
	 * The constructor
	 *
	 * @param string $uri The domain URI.
	 * @throws NullPointerException if the $uri is empty.
	 */
	public function  __construct($uri) {
		if (empty ($uri)) {
			throw new NullPointerException("uri");
		}
		$domain = new Domain();
		$this->exists = $domain->get()->where("%n = %s", Domain::DATA_URI, $uri)->count() > 0;
	}

	public function exists() {
		return $this->exists;
	}

}
?>
