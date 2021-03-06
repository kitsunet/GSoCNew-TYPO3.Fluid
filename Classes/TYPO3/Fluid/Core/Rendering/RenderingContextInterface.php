<?php
namespace TYPO3\Fluid\Core\Rendering;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

interface RenderingContextInterface extends \TYPO3\Base\Core\Rendering\RenderingContextInterface {

	

	/**
	 * Get the controller context which will be passed to the ViewHelper
	 *
	 * @return \TYPO3\Flow\Mvc\Controller\ControllerContext The controller context to set
	 */
	public function getControllerContext();

}

?>
