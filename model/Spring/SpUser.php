<?php
namespace Spring;

use Spring\SpActiveRecord;

class SpUser extends SpActiveRecord
{
	protected $table = 'users';
	
	protected function insert($attributes = null)
	{
		$sql = "INSERT INTO {$this->table} 
				(email, name, last_name, password, user_salt, is_verified, is_active, is_admin, verification_code) 
				VALUES(:email, :name, :last_name, :password, :user_salt, :is_verified, :is_active, :is_admin, :verification_code)";
		$this->conn->query($sql);
		$this->conn->bind(':email', $this->email);
		$this->conn->bind(':name', $this->name);
		$this->conn->bind(':last_name', $this->last_name);
		$this->conn->bind(':password', $this->password);
		$this->conn->bind(':user_salt', $this->user_salt);
		$this->conn->bind(':is_verified', $this->is_verified);
		$this->conn->bind(':is_active', $this->is_active);
		$this->conn->bind(':is_admin', $this->is_admin);
		$this->conn->bind(':verification_code', $this->verification_code);
		$this->conn->execute();
	}
	
	/**
	 * Find user by email, and populates user object on success
	 * 
	 * @param string $email
	 * @return bool
	 */
	public function findByEmail($email = 'null')
	{
		if ($email != null) {
			$this->conn->query ("SELECT * FROM users WHERE email = :email");
			$this->conn->bind(':email', $email);
			$this->conn->execute();
			// If there is a matching email populate the user object
			if ($this->conn->rowCount() > 0) {
				$attribues = array();
				foreach ($this->conn->single() as $attribute => $value) {
					$attributes[$attribute] = $value;
				}
				$this->setAttributes($attributes);
			}
			return true;
		}
		return false;
	}
}