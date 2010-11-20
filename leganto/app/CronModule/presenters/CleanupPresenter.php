<?php
class Cron_CleanupPresenter extends Cron_BasePresenter
{

	public function renderPasswordCodes() {
		// Remove old new pass codes
		Leganto::users()->getUpdater()->removeOldPassCodes();
	}

}

