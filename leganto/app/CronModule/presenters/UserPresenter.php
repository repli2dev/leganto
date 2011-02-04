<?php
class UserPresenter extends Cron_BasePresenter
{

	public function renderAchievements() {
		dibi::begin();
		dibi::query("DROP TABLE IF EXISTS [tmp_achievement]");
		dibi::query("CREATE TABLE [tmp_achievement] (INDEX ([id_user])) AS SELECT * FROM [view_achievement]");
		dibi::commit();
	}

}
