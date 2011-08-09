<?php

namespace Leganto\DB\Achievement;
use	Leganto\ORM\AEntity;

/**
 * @Id(translate=id_user)
 */
class Entity extends AEntity
{

	/**
	 * @Translate(id_user)
	 */
	protected $idUser;

	/**
	 * @Translate(books_total)
	 */
	protected $booksTotal;

	protected $books;

	/**
	 * @Translate(opinions_total)
	 */
	protected $opinionsTotal;

	protected $opinions;

	/**
	 * @Translate(posts_total)
	 */
	protected $postsTotal;

	protected $posts;

	/**
	 * @Translate(followers_total)
	 */
	protected $followersTotal;

	protected $followers;

}
