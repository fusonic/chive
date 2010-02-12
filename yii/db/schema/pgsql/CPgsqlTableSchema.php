<?php
/**
 * CPgsqlTable class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CPgsqlTable represents the metadata for a PostgreSQL table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CPgsqlTableSchema.php 1678 2010-01-07 21:02:00Z qiang.xue $
 * @package system.db.schema.pgsql
 * @since 1.0
 */
class CPgsqlTableSchema extends CDbTableSchema
{
	/**
	 * @var string name of the schema that this table belongs to.
	 */
	public $schemaName;
}
