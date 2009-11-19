<?php
/** @Id(translate=id_domain) */
class DomainEntity extends AEntity
{

	/** @Translate(id_language) */
	protected $idLanguage;

	/** @Skip(Save) */
	protected $locale;

	protected $uri;

	protected $email;
}
