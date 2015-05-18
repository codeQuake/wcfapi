<?php
namespace api\system\request;

use wcf\system\request\IRoute;

/**
 * Route implementation for api calls.
 * Schema: `v1/{request-stack}`
 * 
 * @author	Jens Krumsieck, Florian Frantzen
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */
class APIRoute implements IRoute {

	/**
	 * parsed request data
	 * @var array<mixed>
	 */
	protected $routeData = array(
		'controller' => 'api',
		'isDefaultController' => false
	);
	
	/**
	 * @see \wcf\system\request\IRoute::buildLink()
	 */
	public function buildLink(array $components) {
		$link = 'v1/';
		
		$request = (isset($components['request'])) ? $components['request'] : '';
		
		if (!empty($request)) $link .= $request.'/';
		
		// unset special components to prevent appending later
		foreach (array('request', 'application', 'controller', 'id') as $componentName) {
			if (isset($components[$componentName])) {
				unset($components[$componentName]);
			}
			}
		
			// prepend index.php
		if (!URL_OMIT_INDEX_PHP) {
			if (URL_LEGACY_MODE) {
				$link = 'index.php/' . $link;
			} else {
				$link = 'index.php?' . $link;
			}
		}
		
			if (!empty($components)) {
			if (strpos($link, '?') === false) $link .= '?';
			else $link .= '&';
			$link .= http_build_query($components, '', '&');
		}
		
		return $link;
	}
	
	/**
	 * Returns true, if build request points to `\api\action\APIAction`
	 * 
	 * @see	\wcf\system\request\IRoute::canHandle()
	 */
	 public function canHandle(array $components) {
		if (!isset($components['application']) || $components['application'] != 'api') {
			// doesn't point to api => not our business
			return false;
		}
		if ($components['controller'] != 'API') {
			// doesn't point to api call
			return false;
		}
		if (!isset($components['request'])) {
			// no request is made
			return false;
		}
		return true;
	}
	
	/**
	 * @see	\wcf\system\request\IRoute::getRouteData()
	 */
	public function getRouteData() {
		return $this->routeData;
	}
	
	/**
	 * @see	\wcf\system\request\IRoute::isACP()
	 */
	public function isACP() {
		return false;
	}
	/**
	 * @see	\wcf\system\request\IRoute::matches()
	 */
	public function matches($requestURL) {
		if (!URL_LEGACY_MODE) {
			// request URL must be prefixed with `v1/`
			if (substr($requestURL, 0, 3) != 'v1/') {
				return false;
			}
			$request = substr($requestURL, 3, -1);
		} else {
			$request = trim($requestURL, '/');
		}
		
		// validate request
		if (preg_match('~^[a-z0-9]+(?:\-{1}[a-z0-9]+)*(?:\/[a-z0-9]+(?:\-{1}[a-z0-9]+)*)*$~', $request)) {
			$this->routeData['request'] = $request;
			return true;
		}
		return false;
	}
}
