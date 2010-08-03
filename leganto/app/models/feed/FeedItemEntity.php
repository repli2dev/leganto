<?php
/** @Id(translate=id_item) */
class FeedItemEntity extends AEntity
{

    const TYPE_OPINION	= "opinion";

    const TYPE_POST	= "post";

    /** @Translate(id_user) */
    protected $userId;

    /** @Translate(user_nick) */
    protected $userNick;

    protected $type;

    /** @Translate(item_name) */
    protected $name;

    /** @Translate(id_category) */
    protected $categoryId;

    /** @Translate(category_name) */
    protected $categoryName;

    /** @Translate(category_subname) */
    protected $categorySubname;

    protected $content;

    protected $inserted;
}

