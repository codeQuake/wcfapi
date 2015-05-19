<?php
namespace api\action;
use api\data\IRESTfulObject;
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
	 * object to work with
	 * @var object
	 */
	public $object = null;
	
	/**
	 * HTTP Request method
	 * @var string
	 */
	public $method = 'GET';

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
		 * URL api/v1/category/54/news/ => Array ( [controller] => api [request] => category/54/news )
		 */
		$routeData = RouteHandler::getInstance()->getRouteData();
		if (!isset($routeData['request'])) throw new AJAXException('no valid request found');
		$request = $routeData['request'];
		
		//split request in parts
		$splittedRequest = explode('/', $request);		
		$i = 0;
		$request = array();
		foreach ($splittedRequest as $part) {
			if (intval($part) != 0 && $i != 0) {
				//ah it's an ID of the previous object! *bulb*
				$i--;
				
				$id = intval($part);
				if (strpos($part, ';')) {
					//array given
					$id = array_filter(explode(';', $part));
				}
				
				$request[$i]['objectID'] = $id;
			}
			else {
				$request[$i] = array('object' => $part);
			}
			
			$i++;
		}
		
		//we support only two chaining objects like category/XX/news
		$count = count($request);
		if ($count > 2) throw new AJAXException('request overflow');
		
		/**
		 * get request method
		 * available methods: GET, PUT, POST, DELETE
		 */
		$this->method = $_SERVER['REQUEST_METHOD'];
		if (!in_array($this->method, array('GET', 'POST', 'PUT', 'DELETE'))) throw new AJAXException('unknown request method');
		
		//have to use special classes, extending normal dbos and implementing our interface
		$classPrefix = 'api\\data\\';
		
		//only get request needs the database object, the others the dbo-action class...
		$classSuffix = '';
		if ($this->method != 'GET') $classSuffix = 'Action';
		//GET data & last object does not have an id given => list all items
		if ($this->method == 'GET' && !isset($request[$count - 1]['objectID'])) $classSuffix = 'List';
		
		$className = $classPrefix;
		
		//dbo is found in last given objects folder
		$className .= $request[$count-1]['object'];
		$className .= '\\'.ucfirst($request[0]['object']);
		if ($count > 1) $className .= ucfirst($request[$count-1]['object']);
		$className .= $classSuffix;
		
		//get object id, taken from the first request object
		$objectID = 0;
		if (isset($request[0]['objectID'])) $objectID = $request[0]['objectID'];
		
		//no creation context -> specify object id
		if ($count > 1 && ((!is_array($objectID) && $objectID == 0) || (is_array($objectID) && empty($objectID))) && $this->method != 'POST') throw new AJAXException('no object specified');
		
		//call class if exists
		if (!class_exists($className)) throw new AJAXException("unable to find class '".$className."'");
		if ($this->method == 'GET') {
			$this->object = new $className($objectID);
			
			//dbo must implement our interface
			if (!($this->object instanceof IRESTfulObject)) throw new AJAXException("given object does not implement api\\data\\IRESTfulObject");
		}
		else {
			//calls the action
			switch ($this->method) {
				case 'POST': 
					//create
					$this->object = new $className(array(), 'create', $this->data);
					break;
				case 'PUT':
					//update
					$this->object = new $className(array($objectID), 'update', $this->data);
					break;
				case 'DELETE':
					//delete
					$this->object = new $className(array($objectID), 'delete');
					break;
				default:
					throw new AJAXException("one does not simply walk into mordor!");
					break;
			}
			$this->object->executeAction();
		} 
		//print the JSON bullshit
		$this->executed();
	}
	
	/**
	 * @see wcf\action\AbstractAction::executed()
	 */
	public function executed() {
		 if ($this->method == 'GET') $this->sendJsonResponse($this->object->getAPIData());
		 
		 else $this->sendJsonResponse(array('success'));
	}
}
