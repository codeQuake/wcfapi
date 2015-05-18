<?php
namespace api\action;
use wcf\action\AbstractAjaxAction;
use wcf\system\exception\AJAXException;
use wcf\system\request\RouteHandler;

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
		
		/**
		 * @var $routeData array<mixed>
		 * contains:
		 * Array ( 
		 *		[controller] => api 
		 *		[request] => request string 
		 *	)
		 * example:
		 * URL api/v1/thread/2/post/1447/ => Array ( [controller] => api [request] => thread/2/post/1447 )
		 */
		$routeData = RouteHandler::getInstance()->getRouteData();
		$request = $routeData['request'];
		
		/**
		 * get request method
		 * available methods: GET, PUT, POST, DELETE
		 */
		$method = $_SERVER['REQUEST_METHOD'];
		if (!in_array($method, array('GET', 'POST', 'PUT', 'DELETE'))) throw new AJAXException('unknown request method');
	}
}
