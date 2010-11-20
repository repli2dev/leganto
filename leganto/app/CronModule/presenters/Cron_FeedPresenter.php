<?php
class Cron_FeedPresenter extends Cron_BasePresenter
{

	public function renderPrepare() {
		dibi::begin();
		dibi::query("DROP TABLE IF EXISTS [tmp_feed]");
		dibi::query("CREATE TABLE [tmp_feed] (INDEX ([id_user])) AS SELECT * FROM [view_feed]");
		dibi::commit();
	}

}
