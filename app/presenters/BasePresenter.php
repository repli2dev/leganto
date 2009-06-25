<?php
/**
 * This presenter should be extended by all presenters.
 *
 * @author Jan Papousek
 */
abstract class BasePresenter extends Presenter
{
    protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();

		$template->registerFilter('CurlyBracketsFilter::invoke');

		return $template;
	}

	/**
	 * Creates a component - calls an method $this->create{Name}
	 * @param string $name the name of the compoenent to create
	 */
	protected function createComponent($name) {
		if (String::lower($name) === "component") {
			throw new InvalidArgumentException("Argument name '$name' is not allowed.");
		}

		$callback = array($this, "create" . ucfirst($name));

		if (method_exists($callback[0], $callback[1])) {
			$component = call_user_func($callback, $name);

			if ($component instanceof IComponent && $component->getParent() === NULL) {
				$this->addComponent($component, $name);
			}

		}
		else {
			parent::createComponent($name);
		}
	}
}
