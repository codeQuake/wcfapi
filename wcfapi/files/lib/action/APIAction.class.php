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
		 * example: (user comments)
		 * URL api/v1/user/2/comment/1447/ => Array ( [controller] => api [request] => user/2/comment/1447 )
		 */
		$routeData = RouteHandler::getInstance()->getRouteData();
		$request = $routeData['request'];
		
		//split request in parts
		$splittedRequest = explode('/', $request);
		$i = 0;
		$request = array();
		foreach ($splittedRequest as $part) {
			if (intval($part) != 0 && $i != 0) {
				//ah it's an ID of the previous object! *bulb*
				$i--;
				$request[$i]['objectID'] = intval($part);
			}
			else {
				$request[$i] = array('object' => $part);
			}
			
			$i++;
		}
		
		/**
		 * get request method
		 * available methods: GET, PUT, POST, DELETE
		 */
		$method = $_SERVER['REQUEST_METHOD'];
		if (!in_array($method, array('GET', 'POST', 'PUT', 'DELETE'))) throw new AJAXException('unknown request method');
		
		//have to use special classes, extending normal dbos and implementing our interface
		$classPrefix = 'api\\data\\';
		
		//only get request needs the database object, the others the dbo-action class...
		$classSuffix = '';
		if ($method != 'GET') $classSuffix = 'Action';
		
		$count = count($request);
		$i = 0;
		foreach ($request as $part) {
			$request[$i]['className'] = $classPrefix.$part['object'].'\\'.ucfirst($part['object']);
			//only the last object needs the action suffix (like editing a comment on an element)
			if ($i == $count) $request[$i]['className'] .= $classSuffix;
			
			//deactivated for debugging
			//if (!class_exists($request[$i]['className'])) throw new AJAXException("unable to find class '".$request[$i]['className']."'");
			$i++;
		}
		
		var_dump($request);
		/**
		 * results:
		 * array(2) { 
		 *	[0]=> array(3) { 
		 *		["object"]=> string(4) "user" 
		 *		["objectID"]=> int(1) 
		 *		["className"]=> string(18) "api\data\user\User" } 
		 *	[1]=> array(3) { 
		 *		["object"]=> string(7) "comment" 
		 *		["objectID"]=> int(2) 
		 *		["className"]=> string(24) "api\data\comment\Comment" } 
		 *	}
		 *
		 * any suggestions??
		 */
		
	}
}
