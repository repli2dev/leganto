<?php
/**
 * @author Jan Papousek
 */
class UserAuthenticator
{

	public function authenticate(array $credentials) {
		$email = $credentials[IAuthenticator::USERNAME];
		$password = self::passwordHash($credentials[IAuthenticator::PASSWORD]);
		$row = Leganto::users()->getSelector()->findAll()->where("[email] = %s", $email)->fetch();
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
		return new Identity($row['nick'], $row["role"], array("id_user" => $row["id_user"]));
	}

	// FIXME: donacist role a nick
	public function authenticateByToken($token){
		if(empty($token)) return;

		$row = SimpleTableModel::createTableModel("access")->findAll()->where("[token] = %s", $token)->fetch();
		if($row->count() == 0) return; // Token do not exist!
		$identity = new Identity($row['nick'], $row["role"], array("id_user" => $row["id_user"]));
		return $identity;
	}

	public static function passwordHash($password) {
		return sha1($password);
	}

}

