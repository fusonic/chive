<?php
/**
 * CHttpException class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CHttpException represents an exception caused by invalid operations of end-users.
 *
 * The HTTP error code can be obtained via {@link statusCode}.
 * Error handlers may use this status code to decide how to format the error page.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CHttpException.php 1678 2010-01-07 21:02:00Z qiang.xue $
 * @package system.base
 * @since 1.0
 */
class CHttpException extends CException
{
	/**
	 * @var integer HTTP status code, such as 403, 404, 500, etc.
	 */
	public $statusCode;

	/**
	 * Constructor.
	 * @param integer HTTP status code, such as 404, 500, etc.
	 * @param string error message
	 * @param integer error code
	 */
	public function __construct($status,$message=null,$code=0)
	{
		$this->statusCode=$status;
		parent::__construct($message,$code);
	}
}
