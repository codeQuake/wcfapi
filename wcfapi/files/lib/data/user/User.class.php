<?php
namespace api\data\user;
use api\data\IRESTfulObject;
use wcf\data\user\UserProfile;

/**
 * Representes user based api operations
 * 
 * @author	Jens Krumsieck
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */

class User extends UserProfile implements IRESTfulObject {
	/**
	 * @see wcf\data\DatasbaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wcf\data\user\User';
	
	/**
	 * data fields for REST API
	 * @var array<string>
	 */
	public $fields = array('userID', 'username', 'languageID', 'registrationDate');
	
	/**
	 * @see wcf\data\DatabaseObjectDecorator::__construct()
	 */
	public function __construct($objectID) {		
		$this->object = new static::$baseClass($objectID);
	}
	
	/**
	 * @see api\data\IRESTfulObject::getAPIData()
	 */
	public function getAPIData() {
		$data = array();
		
		if ($this->canViewEmailAddress) {
			$this->fields[] = 'email';
		}
		
		
		foreach ($this->fields as $field) {
			$data[$field] = $this->{$field};
		}
		
		if ($this->canSeeAvatar()) {
			$data['avatar'] = $this->getAvatar()->getURL();
		}
		
		if ($this->canViewProfile == 0) {
			return $data;
		}
		else {
			// return only userID so we know there is an user but hidden
			return array('userID');
		}
	}

}