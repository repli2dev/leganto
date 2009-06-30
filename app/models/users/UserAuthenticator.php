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
			Users::DATA_EMAIL,
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
		$password = Users::passwordHash(
			$credentials[IAuthenticator::PASSWORD],
			$user[Users::DATA_EMAIL]
		);
		if ($user[Users::DATA_PASSWORD] !== $password) {
			throw new AuthenticationException(
				"Inavalid password.",
				AuthenticationException::INVALID_CREDENTIAL
			);
		}
		// Last logged
		$users->update($user[Users::DATA_ID], array(Users::DATA_LAST_LOGGED => new DibiVariable("now()","sql")));
		// Logged user
		unset($user[Users::DATA_PASSWORD]);
		return new Identity(
			$user[Users::DATA_NICKNAME],
			$user[Role::DATA_NAME],
			$user
		);
	}

}
