<?php
/**
 * @author	Jens Krumsieck
 * @copyright	2015 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.api
 */
require_once('./global.php');
wcf\system\request\RequestHandler::getInstance()->handle('api');
