<?php
namespace wcf\system\event\listener;

use api\system\request\APIRoute;
use wcf\system\event\listener\IParameterizedEventListener;

/**
 * Registers api specific routes.
 * Listener has to be placed within wcf namespace because application wouldn't
 * be uninstallable otherwise.
 * 
 * @author	Jens Krumsieck, Florian Frantzen
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */
class APIRouteHandlerListener implements IParameterizedEventListener {

        /**
	 * @see	\wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		// only register routes when an application is active
		if (PACKAGE_ID !== 1) {
			$route = new APIRoute();
			$eventObj->addRoute($route);
		}
	}
}