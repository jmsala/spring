<?php
namespace Spring;

class SpDatabase
{
	private $host 	= DB_HOST;
	private $user	= DB_USER;
	private $pass	= DB_PASS;
	private $dbname	= DB_NAME;
	private $dbh;
	private $stmt;
	private $errors;
	
	/**
	 * Tries to establish a connection to the database
	 * @throws SpException
	 */
	public function __construct()
	{
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		
		$options = array(
			\PDO::ATTR_PERSISTENT 	=> true,
			\PDO::ATTR_ERRMODE 		=> \PDO::ERRMODE_EXCEPTION
		);
		
		try {
			$this->dbh = new \PDO($dsn, $this->user, $this->pass, $options);
		} catch (\PDOException $e) {
			$this->errors = $e->getMessage();
			throw new SpException($e->getMessage(), $e->getCode());
		}
	}
	
	public function query($query)
	{
		$this->stmt = $this->dbh->prepare($query);
	}
	
	/**
	 * Binds a param with its value, securing the types with PDO 
	 * @param string $param
	 * @param string $value
	 * @param string $type
	 * @return bool
	 */
	public function bind($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = \PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = \PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = \PDO::PARAM_NULL;
					break;
				default:
					$type = \PDO::PARAM_STR;
			}
		}
		
		return $this->stmt->bindValue($param, $value, $type);
	}
	
	/**
	 * Executes a prepared statement
	 * @return bool true on success or false on failure  
	 */
	public function execute()
	{
		return $this->stmt->execute();
	}
	
	/**
	 * returns an array of the result set rows
	 * @see \PDOStatement::fetchAll
	 */
	public function resultset()
	{
		$this->execute();
		return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	/**
	 * Returns a single record
	 * 
	 */
	public function single()
	{
		$this->execute();
		return $this->stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	/**
	 * Returns the number of affected rows from previous delete, update or insert
	 */
	public function rowCount()
	{
		return $this->stmt->rowCount();
	}
	
	/**
	 * Returns the last inserted Id as string
	 * @return string
	 */
	public function lastInsertId()
	{
		return $this->dbh->lastInsertId();
	}
	
	/**
	 * Allows you to run multiple changes to a database all in one batch
	 * 
	 * If one fails an exception will be thrown so you should roll back 
	 * any previous changes to the start of the transaction
	 * 
	 * @return bool
	 */
	public function beginTransaction()
	{
		return $this->dbh->beginTransaction();
	}
	
	/**
	 * Commit your changes to end a transaction and 
	 */
	public function endTransaction()
	{
		return $this->dbh->commit();
	}
	
	/**
	 * Cancel a transaction and roll back your changes
	 */
	public function cancelTransaction()
	{
		return $this->dbh->rollBack();
	}
	
	/**
	 * Dumps the the information contained in the Prepared Statement
	 */
	public function debugDumpParams()
	{
		return $this->stmt->debugDumpParams();
	}
}