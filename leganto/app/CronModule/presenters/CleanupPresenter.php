<?php

/**
 * Cron user maintanance
 * @author Jan Papousek
 * @author Jan Drabek
 */

namespace CronModule;

use Leganto\DB\Factory;

class CleanupPresenter extends BasePresenter {

	public function renderPasswordCodes() {
		// Remove old new pass codes
		Factory::user()->getUpdater()->removeOldPassCodes();
	}

}

