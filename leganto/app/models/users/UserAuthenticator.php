<?php
/**
 * @author Jan Papousek
 */
class UserAuthenticator
{

	public function authenticate(array $credentials) {
		// External login
		if(!empty($credentials['extra'])) { // Extra data (possible token) was found in credentials data -> try to find connection and log user in.
			$token = $credentials['extra'];
			$row = Leganto::connections()->getSelector()->findAll()->where("[token] = %s", $token)->fetch();
			if(empty($row)){ // User for this token was not found
				throw new AuthenticationException(
					"The user does not exist.",
					IAuthenticator::IDENTITY_NOT_FOUND
				);
			}
			// Load data connected to that token.
			$row = Leganto::users()->getSelector()->findAll()->where("[id_user] = %i", $row["id_user"])->fetch();
			$name = $row['nick'];
		} else { // Internal login
			$name = $credentials[IAuthenticator::USERNAME];
			$password = self::passwordHash($credentials[IAuthenticator::PASSWORD]);
			$row = Leganto::users()->getSelector()->findAll()->where("[nick] = %s", $name)->fetch();
		}
		// Atuehntication failed -> throw execption.
		if (empty($row)) {
			throw new AuthenticationException(
				"The user does not exist.",
				IAuthenticator::IDENTITY_NOT_FOUND
			);
		}
		if (isset($password) && $row["password"] != $password) {
			throw new AuthenticationException(
				"The password does not match",
				IAuthenticator::INVALID_CREDENTIAL
			);
		}
		// Or return new identity
		return new Identity($name, $row["role"], array("id" => $row["id_user"]));
	}

	public static function passwordHash($password) {
		return sha1($password);
	}

}

