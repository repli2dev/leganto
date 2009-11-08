<?php
/**
 * @author Jan Papousek
 */
class UserAuthenticator
{

	public function authenticate(array $credentials) {
		$name = $credentials[IAuthenticator::USERNAME];
		$password = self::passwordHash($credentials[IAuthenticator::PASSWORD]);
		$row = Leganto::users()->getSelector()->findAll()->where("[nick] = %s", $name)->fetch();
		if (empty($row)) {
			throw new AuthenticationException(
				"The user does not exist.",
				IAuthenticator::IDENTITY_NOT_FOUND
			);
		}
		if ($row["password"] != $password) {
			throw new AuthenticationException(
				"The password does not match",
				IAuthenticator::INVALID_CREDENTIAL
			);
		}
		return new Identity($name, $row["role"], array("id_user" => $row["id_user"]));
	}

	public static function passwordHash($password) {
		return sha1($password);
	}

}

