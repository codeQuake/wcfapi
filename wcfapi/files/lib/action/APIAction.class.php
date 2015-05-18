<?php
namespace api\action;
use wcf\action\AbstractAjaxAction;

/**
 * @author  Jens Krumsieck
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	de.codequake.api
 */

class APIAction extends AbstractAjaxAction {
    
	/**
	 * @see \wcf\action\AbstractAction::execute()
	 */
    public function execute() {
        parent::execute();
		
        $routeData = RouteHandler::getInstance()->getRouteData();
		#DEBUG
        print_r($routeData);
    }
}
