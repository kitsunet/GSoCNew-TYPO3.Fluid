<?php
namespace TYPO3\Fluid\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * Abstract Form View Helper. Bundles functionality related to direct property access of objects in other Form ViewHelpers.
 *
 * If you set the "property" attribute to the name of the property to resolve from the object, this class will
 * automatically set the name and value of a form element.
 *
 * @api
 */
abstract class AbstractFormFieldViewHelper extends \TYPO3\Base\ViewHelpers\Form\AbstractFormViewHelper {

	


	/**
	 * Add additional identity properties in case the current property is hierarchical (of the form "bla.blubb").
	 * Then, [bla][__identity] has to be generated as well.
	 *
	 * @return void
	 */
	protected function addAdditionalIdentityPropertiesIfNeeded() {
		$propertySegments = explode('.', $this->arguments['property']);
		if (count($propertySegments) >= 2) {
				// hierarchical property. If there is no "." inside (thus $propertySegments == 1), we do not need to do anything
			$formObject = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject');

			$objectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
				// If Count == 2 -> we need to go through the for-loop exactly once
			for ($i=1; $i < count($propertySegments); $i++) {
				$object = \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($formObject, implode('.', array_slice($propertySegments, 0, $i)));
				$objectName .= '[' . $propertySegments[$i-1] . ']';
				$hiddenIdentityField = $this->renderHiddenIdentityField($object, $objectName);

					// Add the hidden identity field to the ViewHelperVariableContainer
				$additionalIdentityProperties = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties');
				$additionalIdentityProperties[$objectName] = $hiddenIdentityField;
				$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties', $additionalIdentityProperties);
			}
		}
	}

	/**
	 * Get the current property of the object bound to this form.
	 *
	 * @return mixed Value
	 */
	protected function getPropertyValue() {
		if (!$this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject')) {
			return NULL;
		}
		$formObject = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject');
		$propertyName = $this->arguments['property'];

		if (is_array($formObject)) {
			return isset($formObject[$propertyName]) ? $formObject[$propertyName] : NULL;
		}
		return \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($formObject, $propertyName);
	}

	/**
	 * Internal method which checks if we should evaluate a domain object or just output arguments['name'] and arguments['value']
	 *
	 * @return boolean TRUE if we should evaluate the domain object, FALSE otherwise.
	 */
	protected function isObjectAccessorMode() {
		return $this->hasArgument('property')
			&& $this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
	}

	/**
	 * Add an CSS class if this view helper has errors
	 *
	 * @return void
	 */
	protected function setErrorClassAttribute() {
		if ($this->hasArgument('class')) {
			$cssClass = $this->arguments['class'] . ' ';
		} else {
			$cssClass = '';
		}
		$mappingResultsForProperty = $this->getMappingResultsForProperty();
		if ($mappingResultsForProperty->hasErrors()) {
			if ($this->hasArgument('errorClass')) {
				$cssClass .= $this->arguments['errorClass'];
			} else {
				$cssClass .= 'error';
			}
			$this->tag->addAttribute('class', $cssClass);
		}
	}

	/**
	 * Get errors for the property and form name of this view helper
	 *
	 * @return \TYPO3\Flow\Error\Result
	 */
	protected function getMappingResultsForProperty() {
		if (!$this->isObjectAccessorMode()) {
			return new \TYPO3\Flow\Error\Result();
		}
		$validationResults = $this->controllerContext->getRequest()->getInternalArgument('__submittedArgumentValidationResults');
		if ($validationResults === NULL) {
			return new \TYPO3\Flow\Error\Result();
		}
		$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		return $validationResults->forProperty($formObjectName)->forProperty($this->arguments['property']);
	}

	/**
	 * Renders a hidden field with the same name as the element, to make sure the empty value is submitted
	 * in case nothing is selected. This is needed for checkbox and multiple select fields
	 *
	 * @return void
	 */
	protected function renderHiddenFieldForEmptyValue() {
		$emptyHiddenFieldNames = array();
		if ($this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames')) {
			$emptyHiddenFieldNames = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames');
		}

		$fieldName = $this->getName();
		if (substr($fieldName, -2) === '[]') {
			$fieldName = substr($fieldName, 0, -2);
		}
		if (!in_array($fieldName, $emptyHiddenFieldNames)) {
			$emptyHiddenFieldNames[] = $fieldName;
			$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'emptyHiddenFieldNames', $emptyHiddenFieldNames);
		}
	}
}

?>
