<?php



class DefaultPresenter extends Presenter
{


	public function renderDefault()
	{
		$this->invalidateControl('round');
	}



	/**
	 * Fifteen game control factory.
	 * @return mixed
	 */
	protected function createComponentFifteen()
	{
		$fifteen = new FifteenControl;
		$fifteen->onGameOver[] = callback($this, 'gameOver');
		return $fifteen;
	}



	public function gameOver($sender, $round)
	{
		$this->template->flash = 'Congratulate!';
		$this->invalidateControl('flash');
	}

}
