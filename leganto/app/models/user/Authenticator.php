<?php

/**
 * Authenticator
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 * 				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 */

namespace Leganto\DB\User;

use Leganto\ORM\Workers\ISelector,
    Leganto\DB\Factory,
    Nette\Security\IAuthorizator,
    Nette\Security\IAuthenticator,
    Nette\Security\AuthenticationException,
    Leganto\ACL\Role,
    Nette\Security\Identity,
    Nette\DateTime;

class Authenticator {

	/**
	 * Authenticate user according to credentials (name-password or token)
	 * @param array $credentials credentials
	 * @return Identity
	 */
	public function authenticate(array $credentials) {
		// External login
		if (!empty($credentials['extra'])) { // Extra data (possible token) was found in credentials data -> try to find connection and log user in.
			$token = $credentials['extra'];
			$row = Factory::connection()->getSelector()->findAll()->where("[token] = %s", $token)->fetch();
			if (empty($row)) { // User for this token was not found
				throw new AuthenticationException(
					"The user does not exist.",
					IAuthenticator::IDENTITY_NOT_FOUND
				);
			}
			// Load data connected to that token.
			$row = Factory::user()
				->fetchAndCreate(
				Factory::user()->getSelector()->findAll()->where("[id_user] = %i", $row["id_user"])->applyLimit(1)
			);
			$name = $row->nickname;
		} else { // Internal login
			$name = $credentials[IAuthenticator::USERNAME];
			$password = self::passwordHash($credentials[IAuthenticator::PASSWORD]);
			$row = Factory::user()->getSelector()->findByNick($name);
		}
		// Atuehntication failed -> throw execption.
		if (empty($row)) {
			throw new AuthenticationException(
				"The user does not exist.",
				IAuthenticator::IDENTITY_NOT_FOUND
			);
		}
		if (isset($password) && $row->password != $password) {
			throw new AuthenticationException(
				"The password does not match",
				IAuthenticator::INVALID_CREDENTIAL
			);
		}
		// Update datas (remove key for new password)
		$row->lastLogged = new DateTime();
		$row->newPassTime = 'NULL';
		$row->newPassKey = 'NULL';
		$row->persist();
		// Role
		$role = Role::getRoleDescriptor($row);

		// Or return new identity
		return new Identity($name, $role, array("id" => $row->getId()));
	}

	/**
	 * Create password hash (SHA1)
	 * @param string $password noncrypted password
	 * @return string sha1ed password
	 */
	public static function passwordHash($password) {
		return sha1($password);
	}

}

