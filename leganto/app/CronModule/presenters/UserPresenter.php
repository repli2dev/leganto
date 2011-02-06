<?php
class Cron_UserPresenter extends Cron_BasePresenter
{

	public function renderAchievements() {
		dibi::begin();
		dibi::query("DROP TABLE IF EXISTS [tmp_achievement]");
		dibi::query("CREATE TABLE [tmp_achievement] (INDEX ([id_user])) AS SELECT * FROM [view_achievement]");
		dibi::commit();
	}

	public function renderPrivileges() {
		dibi::begin();
		$users = dibi::query("SELECT [id_user] FROM [tmp_achievement] WHERE [opinions] >= 3")->fetchPairs("id_user", "id_user");
		dibi::update("user", array(
			"role"	=> "moderator"
		))->where("[role] = 'common' AND [id_user] IN %l", $users)->execute();
		dibi::commit();
	}

}
