<?php
namespace api\data;

/**
 * Interface for API Objects
 * 
 * @author	Jens Krumsieck
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */

interface IRESTfulObject {

	/**
	 * Returns an array containing the required data for REST API
	 * 
	 * @return	string
	 */
	public function getAPIData();
}