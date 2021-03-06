<?php
namespace TYPO3\Fluid\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


use TYPO3\Flow\Annotations as Flow;

/**
 * Grouped loop view helper.
 * Loops through the specified values.
 *
 * The groupBy argument also supports property paths.
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:groupedFor each="{0: {name: 'apple', color: 'green'}, 1: {name: 'cherry', color: 'red'}, 2: {name: 'banana', color: 'yellow'}, 3: {name: 'strawberry', color: 'red'}}" as="fruitsOfThisColor" groupBy="color">
 *   <f:for each="{fruitsOfThisColor}" as="fruit">
 *     {fruit.name}
 *   </f:for>
 * </f:groupedFor>
 * </code>
 * <output>
 * apple cherry strawberry banana
 * </output>
 *
 * <code title="Two dimensional list">
 * <ul>
 *   <f:groupedFor each="{0: {name: 'apple', color: 'green'}, 1: {name: 'cherry', color: 'red'}, 2: {name: 'banana', color: 'yellow'}, 3: {name: 'strawberry', color: 'red'}}" as="fruitsOfThisColor" groupBy="color" groupKey="color">
 *     <li>
 *       {color} fruits:
 *       <ul>
 *         <f:for each="{fruitsOfThisColor}" as="fruit" key="label">
 *           <li>{label}: {fruit.name}</li>
 *         </f:for>
 *       </ul>
 *     </li>
 *   </f:groupedFor>
 * </ul>
 * </code>
 * <output>
 * <ul>
 *   <li>green fruits
 *     <ul>
 *       <li>0: apple</li>
 *     </ul>
 *   </li>
 *   <li>red fruits
 *     <ul>
 *       <li>1: cherry</li>
 *     </ul>
 *     <ul>
 *       <li>3: strawberry</li>
 *     </ul>
 *   </li>
 *   <li>yellow fruits
 *     <ul>
 *       <li>2: banana</li>
 *     </ul>
 *   </li>
 * </ul>
 * </output>
 *
 * Note: Using this view helper can be a sign of weak architecture. If you end up using it extensively
 * you might want to fine-tune your "view model" (the data you assign to the view).
 *
 * @api
 */
class GroupedForViewHelper extends \TYPO3\Base\ViewHelpers\GroupedForViewHelper {


/**
	 * Controller Context to use
	 * @var \TYPO3\Flow\Mvc\Controller\ControllerContext
	 * @api
	 */
	protected $controllerContext;

	

	/**
	 * Reflection service
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @param \TYPO3\Flow\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\Flow\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * @param \TYPO3\Flow\Log\SystemLoggerInterface $systemLogger
	 * @return void
	 */
	public function injectSystemLogger(\TYPO3\Flow\Log\SystemLoggerInterface $systemLogger) {
		$this->systemLogger = $systemLogger;
	}


	/**
	 * Inject a Reflection service
	 * @param \TYPO3\Flow\Reflection\ReflectionService $reflectionService Reflection service
	 */
	public function injectReflectionService(\TYPO3\Flow\Reflection\ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}
	
}

?>
