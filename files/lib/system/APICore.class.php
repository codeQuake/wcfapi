<?php
namespace api\system;
use wcf\system\application\AbstractApplication;

/** 
 * @author	Jens Krumsieck
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */

class APICore extends AbstractApplication {
	/**
	 * @see \wcf\system\application\AbstractApplication::$abbreviation
	 */
	protected $abbreviation = 'api';
	
	/**
	 * @see \wcf\system\application\AbstractApplication::$primaryController
	 */
	protected $primaryController = 'api\action\APIAction';
}
