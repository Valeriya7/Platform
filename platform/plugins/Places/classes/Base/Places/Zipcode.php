<?php

/**
 * Autogenerated base class representing zipcode rows
 * in the Places database.
 *
 * Don't change this file, since it can be overwritten.
 * Instead, change the Places_Zipcode.php file.
 *
 * @module Places
 */
/**
 * Base class representing 'Zipcode' rows in the 'Places' database
 * @class Base_Places_Zipcode
 * @extends Db_Row
 *
 * @property {string} $countryCode
 * @property {string} $zipcode
 * @property {string} $placeName
 * @property {string} $stateName
 * @property {string} $state
 * @property {string} $regionName
 * @property {string} $region
 * @property {string} $community
 * @property {float} $latitude
 * @property {float} $longitude
 * @property {integer} $accuracy
 */
abstract class Base_Places_Zipcode extends Db_Row
{
	/**
	 * @property $countryCode
	 * @type {string}
	 */
	/**
	 * @property $zipcode
	 * @type {string}
	 */
	/**
	 * @property $placeName
	 * @type {string}
	 */
	/**
	 * @property $stateName
	 * @type {string}
	 */
	/**
	 * @property $state
	 * @type {string}
	 */
	/**
	 * @property $regionName
	 * @type {string}
	 */
	/**
	 * @property $region
	 * @type {string}
	 */
	/**
	 * @property $community
	 * @type {string}
	 */
	/**
	 * @property $latitude
	 * @type {float}
	 */
	/**
	 * @property $longitude
	 * @type {float}
	 */
	/**
	 * @property $accuracy
	 * @type {integer}
	 */
	/**
	 * The setUp() method is called the first time
	 * an object of this class is constructed.
	 * @method setUp
	 */
	function setUp()
	{
		$this->setDb(self::db());
		$this->setTable(self::table());
		$this->setPrimaryKey(
			array (
			)
		);
	}

	/**
	 * Connects to database
	 * @method db
	 * @static
	 * @return {iDb} The database object
	 */
	static function db()
	{
		return Db::connect('Places');
	}

	/**
	 * Retrieve the table name to use in SQL statement
	 * @method table
	 * @static
	 * @param {boolean} [$with_db_name=true] Indicates wheather table name should contain the database name
 	 * @return {string|Db_Expression} The table name as string optionally without database name if no table sharding
	 * was started or Db_Expression class with prefix and database name templates is table was sharded
	 */
	static function table($with_db_name = true)
	{
		if (Q_Config::get('Db', 'connections', 'Places', 'indexes', 'Zipcode', false)) {
			return new Db_Expression(($with_db_name ? '{$dbname}.' : '').'{$prefix}'.'zipcode');
		} else {
			$conn = Db::getConnection('Places');
  			$prefix = empty($conn['prefix']) ? '' : $conn['prefix'];
  			$table_name = $prefix . 'zipcode';
  			if (!$with_db_name)
  				return $table_name;
  			$db = Db::connect('Places');
  			return $db->dbName().'.'.$table_name;
		}
	}
	/**
	 * The connection name for the class
	 * @method connectionName
	 * @static
	 * @return {string} The name of the connection
	 */
	static function connectionName()
	{
		return 'Places';
	}

	/**
	 * Create SELECT query to the class table
	 * @method select
	 * @static
	 * @param {array} [$fields='*'] The fields as strings, or array of alias=>field
	 * @param {string} [$alias=null] The tables as strings, or array of alias=>table
	 * @return {Db_Query_Mysql} The generated query
	 */
	static function select($fields, $alias = null)
	{
		if (!isset($alias)) $alias = '';
		$q = self::db()->select($fields, self::table().' '.$alias);
		$q->className = 'Places_Zipcode';
		return $q;
	}

	/**
	 * Create UPDATE query to the class table
	 * @method update
	 * @static
	 * @param {string} [$alias=null] Table alias
	 * @return {Db_Query_Mysql} The generated query
	 */
	static function update($alias = null)
	{
		if (!isset($alias)) $alias = '';
		$q = self::db()->update(self::table().' '.$alias);
		$q->className = 'Places_Zipcode';
		return $q;
	}

	/**
	 * Create DELETE query to the class table
	 * @method delete
	 * @static
	 * @param {object} [$table_using=null] If set, adds a USING clause with this table
	 * @param {string} [$alias=null] Table alias
	 * @return {Db_Query_Mysql} The generated query
	 */
	static function delete($table_using = null, $alias = null)
	{
		if (!isset($alias)) $alias = '';
		$q = self::db()->delete(self::table().' '.$alias, $table_using);
		$q->className = 'Places_Zipcode';
		return $q;
	}

	/**
	 * Create INSERT query to the class table
	 * @method insert
	 * @static
	 * @param {object} [$fields=array()] The fields as an associative array of `column => value` pairs
	 * @param {string} [$alias=null] Table alias
	 * @return {Db_Query_Mysql} The generated query
	 */
	static function insert($fields = array(), $alias = null)
	{
		if (!isset($alias)) $alias = '';
		$q = self::db()->insert(self::table().' '.$alias, $fields);
		$q->className = 'Places_Zipcode';
		return $q;
	}
	/**
	 * Inserts multiple rows into a single table, preparing the statement only once,
	 * and executes all the queries.
	 * @method insertManyAndExecute
	 * @static
	 * @param {array} [$rows=array()] The array of rows to insert. 
	 * (The field names for the prepared statement are taken from the first row.)
	 * You cannot use Db_Expression objects here, because the function binds all parameters with PDO.
	 * @param {array} [$options=array()]
	 *   An associative array of options, including:
	 *
	 * * "chunkSize" {integer} The number of rows to insert at a time. defaults to 20.<br/>
	 * * "onDuplicateKeyUpdate" {array} You can put an array of fieldname => value pairs here,
	 * 		which will add an ON DUPLICATE KEY UPDATE clause to the query.
	 *
	 */
	static function insertManyAndExecute($rows = array(), $options = array())
	{
		self::db()->insertManyAndExecute(
			self::table(), $rows,
			array_merge($options, array('className' => 'Places_Zipcode'))
		);
	}
	
	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_countryCode
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_countryCode($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('countryCode', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".countryCode");
		if (strlen($value) > 2)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".countryCode");
		return array('countryCode', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the countryCode field
	 * @return {integer}
	 */
	function maxSize_countryCode()
	{

		return 2;			
	}

	/**
	 * Returns schema information for countryCode column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_countryCode()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '2',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => 'MUL',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_zipcode
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_zipcode($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('zipcode', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".zipcode");
		if (strlen($value) > 10)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".zipcode");
		return array('zipcode', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the zipcode field
	 * @return {integer}
	 */
	function maxSize_zipcode()
	{

		return 10;			
	}

	/**
	 * Returns schema information for zipcode column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_zipcode()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '10',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_placeName
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_placeName($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('placeName', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".placeName");
		if (strlen($value) > 180)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".placeName");
		return array('placeName', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the placeName field
	 * @return {integer}
	 */
	function maxSize_placeName()
	{

		return 180;			
	}

	/**
	 * Returns schema information for placeName column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_placeName()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '180',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_stateName
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_stateName($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('stateName', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".stateName");
		if (strlen($value) > 100)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".stateName");
		return array('stateName', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the stateName field
	 * @return {integer}
	 */
	function maxSize_stateName()
	{

		return 100;			
	}

	/**
	 * Returns schema information for stateName column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_stateName()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '100',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_state
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_state($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('state', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".state");
		if (strlen($value) > 20)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".state");
		return array('state', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the state field
	 * @return {integer}
	 */
	function maxSize_state()
	{

		return 20;			
	}

	/**
	 * Returns schema information for state column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_state()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '20',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_regionName
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_regionName($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('regionName', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".regionName");
		if (strlen($value) > 100)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".regionName");
		return array('regionName', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the regionName field
	 * @return {integer}
	 */
	function maxSize_regionName()
	{

		return 100;			
	}

	/**
	 * Returns schema information for regionName column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_regionName()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '100',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_region
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_region($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('region', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".region");
		if (strlen($value) > 20)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".region");
		return array('region', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the region field
	 * @return {integer}
	 */
	function maxSize_region()
	{

		return 20;			
	}

	/**
	 * Returns schema information for region column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_region()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '20',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if value is string of length within acceptable limit.
	 * Optionally accept numeric value which is converted to string
	 * @method beforeSet_community
	 * @param {string} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not string or is exceedingly long
	 */
	function beforeSet_community($value)
	{
		if (!isset($value)) {
			$value='';
		}
		if ($value instanceof Db_Expression) {
			return array('community', $value);
		}
		if (!is_string($value) and !is_numeric($value))
			throw new Exception('Must pass a string to '.$this->getTable().".community");
		if (strlen($value) > 100)
			throw new Exception('Exceedingly long value being assigned to '.$this->getTable().".community");
		return array('community', $value);			
	}

	/**
	 * Returns the maximum string length that can be assigned to the community field
	 * @return {integer}
	 */
	function maxSize_community()
	{

		return 100;			
	}

	/**
	 * Returns schema information for community column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_community()
	{

return array (
  0 => 
  array (
    0 => 'varchar',
    1 => '100',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	function beforeSet_latitude($value)
	{
		if ($value instanceof Db_Expression) {
			return array('latitude', $value);
		}
		if (!is_numeric($value))
			throw new Exception('Non-numeric value being assigned to '.$this->getTable().".latitude");
		$value = floatval($value);
		return array('latitude', $value);			
	}

	/**
	 * Returns schema information for latitude column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_latitude()
	{

return array (
  0 => 
  array (
    0 => 'double',
    1 => '100',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => 'MUL',
  3 => NULL,
);			
	}

	function beforeSet_longitude($value)
	{
		if ($value instanceof Db_Expression) {
			return array('longitude', $value);
		}
		if (!is_numeric($value))
			throw new Exception('Non-numeric value being assigned to '.$this->getTable().".longitude");
		$value = floatval($value);
		return array('longitude', $value);			
	}

	/**
	 * Returns schema information for longitude column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_longitude()
	{

return array (
  0 => 
  array (
    0 => 'double',
    1 => '100',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => 'MUL',
  3 => NULL,
);			
	}

	/**
	 * Method is called before setting the field and verifies if integer value falls within allowed limits
	 * @method beforeSet_accuracy
	 * @param {integer} $value
	 * @return {array} An array of field name and value
	 * @throws {Exception} An exception is thrown if $value is not integer or does not fit in allowed range
	 */
	function beforeSet_accuracy($value)
	{
		if ($value instanceof Db_Expression) {
			return array('accuracy', $value);
		}
		if (!is_numeric($value) or floor($value) != $value)
			throw new Exception('Non-integer value being assigned to '.$this->getTable().".accuracy");
		$value = intval($value);
		if ($value < -2147483648 or $value > 2147483647) {
			$json = json_encode($value);
			throw new Exception("Out-of-range value $json being assigned to ".$this->getTable().".accuracy");
		}
		return array('accuracy', $value);			
	}

	/**
	 * @method maxSize_accuracy
	 * Returns the maximum integer that can be assigned to the accuracy field
	 * @return {integer}
	 */
	function maxSize_accuracy()
	{

		return 2147483647;			
	}

	/**
	 * Returns schema information for accuracy column
	 * @return {array} [[typeName, displayRange, modifiers, unsigned], isNull, key, default]
	 */
	static function column_accuracy()
	{

return array (
  0 => 
  array (
    0 => 'int',
    1 => '11',
    2 => '',
    3 => false,
  ),
  1 => false,
  2 => '',
  3 => NULL,
);			
	}

	/**
	 * Retrieves field names for class table
	 * @method fieldNames
	 * @static
	 * @param {string} [$table_alias=null] If set, the alieas is added to each field
	 * @param {string} [$field_alias_prefix=null] If set, the method returns associative array of `'prefixed field' => 'field'` pairs
	 * @return {array} An array of field names
	 */
	static function fieldNames($table_alias = null, $field_alias_prefix = null)
	{
		$field_names = array('countryCode', 'zipcode', 'placeName', 'stateName', 'state', 'regionName', 'region', 'community', 'latitude', 'longitude', 'accuracy');
		$result = $field_names;
		if (!empty($table_alias)) {
			$temp = array();
			foreach ($result as $field_name)
				$temp[] = $table_alias . '.' . $field_name;
			$result = $temp;
		} 
		if (!empty($field_alias_prefix)) {
			$temp = array();
			reset($field_names);
			foreach ($result as $field_name) {
				$temp[$field_alias_prefix . current($field_names)] = $field_name;
				next($field_names);
			}
			$result = $temp;
		}
		return $result;			
	}
};