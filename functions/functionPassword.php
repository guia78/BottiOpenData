<?php
/* 
 * Function password hash security
 * 
 * string password_hash ( string $password , int $algo [, array $options ] )
 * password_hash() creates a new password hash using a strong one-way hashing algorithm. password_hash() is compatible with crypt(). 
 * Therefore, password hashes created by crypt() can be used with password_hash(). 
 * Manual: http://php.net/manual/en/function.password-hash.php
 * 
 */
 
function create_hash($password){
	$hash = password_hash($password, PASSWORD_BCRYPT);
	return $hash;
}

function validate_password($userPassword, $hashedPassword){
	if (password_verify($userPassword, $hashedPassword)) {
		return true;
	} else {
		return false;
	}
}