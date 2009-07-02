<?php
/**
 * This class should be implemented by all components
 *
 * @author papi
 */
abstract class BaseControl extends Control
{

	public function createTemplate() {
		$template = parent::createTemplate();

		// register filters
		$template->registerFilter('CurlyBracketsFilter::invoke');

		// register custom helpers
		$template->registerHelper("date", Helpers::getHelper('date'));
		$template->registerHelper("time", Helpers::getHelper('time'));
		$template->registerHelper("texy", Helpers::getHelper("texy"));

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