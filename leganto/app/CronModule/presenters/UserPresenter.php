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

	public function renderRecommendedBooks() {
		dibi::begin();
		dibi::query("DROP TABLE IF EXISTS [tmp_recommended_book]");
		$users = dibi::query("SELECT DISTINCT([id_user]) FROM [opinion]")->fetchPairs("id_user", "id_user");
		$queries = array();
		foreach ($users AS $user) {
			$queries[] = "
				(SELECT
					[opinion].[id_user],
					[tmp_similar_book_prepared].*
				FROM [opinion]
				INNER JOIN [book_title] USING([id_book_title])
				INNER JOIN [tmp_similar_book_prepared] ON [book_title].[id_book] = [tmp_similar_book_prepared].[id_book_from]
				WHERE [opinion].[rating] BETWEEN 4 AND 5
				AND [id_user] = $user
				AND [tmp_similar_book_prepared].[id_book_title] NOT IN
					(SELECT [help].[id_book_title] FROM [opinion] AS [help] WHERE [id_user] = [opinion].[id_user])
				GROUP BY [opinion].[id_user], [tmp_similar_book_prepared].[id_book_title]
				ORDER BY (2*[opinion].[rating]+[tmp_similar_book_prepared].[rating])*[tmp_similar_book_prepared].[similarity] DESC
				LIMIT 50)";
		}
		$select = implode($queries, " UNION ");
		dibi::query("CREATE TABLE [tmp_recommended_book] (INDEX ([id_user]), INDEX ([id_book_title])) AS $select");
		dibi::commit();
	}

}
