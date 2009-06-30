<?php
/**
 * This class provides authentization
 *
 * @author Jan Papousek
 */
class UserAuthenticator implements IAuthenticator
{


	public function authenticate(array $credentials) {
		$users = new Users();
		// Loading data from database
		$rows = $users->get()->where(
			"%n = %s",
			self::DATA_EMAIL,
			$credentials[IAuthenticator::USERNAME]
		);
		// Does the user exist?
		if ($rows->count() == 0) {
			throw new AuthenticationException(
				"Username not found.",
				AuthenticationException::IDENTITY_NOT_FOUND
			);
		}
		$user = $rows->fetch();
		// Is there a valid password
		$password = self::passwordHash(
			$credentials[IAuthenticator::PASSWORD],
			$user[self::DATA_EMAIL]
		);
		if ($user[self::DATA_PASSWORD] !== $password) {
			throw new AuthenticationException(
				"Inavalid password.",
				AuthenticationException::INVALID_CREDENTIAL
			);
		}
		// Last logged
		$users->update($id, array(self::DATA_LAST_LOGGED => new DibiVariable("now()","sql")));
		// Logged user
		unset($user[self::DATA_PASSWORD]);
		return new Identity(
			$user[self::DATA_NICKNAME],
			$user[Role::DATA_NAME],
			$user
		);
	}

}
