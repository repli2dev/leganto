<?php

/**
 * Test: User authentication.
 *
 * @author     David Grudl
 * @package    Nette\Web
 * @subpackage UnitTests
 */



require dirname(__FILE__) . '/../bootstrap.php';



// Setup environment
$_COOKIE = array();
ob_start();



class AuthenticationHandler implements IAuthenticator
{
	/*
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	function authenticate(array $credentials)
	{
		if ($credentials[self::USERNAME] !== 'john') {
			throw new AuthenticationException('Unknown user', self::IDENTITY_NOT_FOUND);
		}

		if ($credentials[self::PASSWORD] !== 'xxx') {
			throw new AuthenticationException('Password not match', self::INVALID_CREDENTIAL);
		}

		return new Identity('John Doe', 'admin');
	}

}



function onLoggedIn($user) {
	// TODO: add test
}



function onLoggedOut($user) {
	// TODO: add test
}



$user = new User;
$user->onLoggedIn[] = 'onLoggedIn';
$user->onLoggedOut[] = 'onLoggedOut';


Assert::false( $user->isLoggedIn(), 'isLoggedIn?' );
Assert::null( $user->getIdentity(), 'getIdentity' );



// authenticate
try {
	// login without handler
	$user->login('jane', '');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('InvalidStateException', "Service 'Nette\\Security\\IAuthenticator' not found.", $e );
}

$handler = new AuthenticationHandler;
$user->setAuthenticationHandler($handler);

try {
	// login as jane
	$user->login('jane', '');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('AuthenticationException', 'Unknown user', $e );
}

try {
	// login as john
	$user->login('john', '');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::exception('AuthenticationException', 'Password not match', $e );
}

// login as john#2
$user->login('john', 'xxx');
Assert::true( $user->isLoggedIn(), 'isLoggedIn?' );
Assert::equal( new Identity('John Doe', 'admin'), $user->getIdentity(), 'getIdentity' );



// log out
// logging out...
$user->logout(FALSE);

Assert::false( $user->isLoggedIn(), 'isLoggedIn?' );
Assert::equal( new Identity('John Doe', 'admin'), $user->getIdentity(), 'getIdentity' );


// logging out and clearing identity...
$user->logout(TRUE);

Assert::false( $user->isLoggedIn(), 'isLoggedIn?' );
Assert::null( $user->getIdentity(), 'getIdentity' );



// namespace
// login as john#2?
$user->login('john', 'xxx');
Assert::true( $user->isLoggedIn(), 'isLoggedIn?' );


// setNamespace(...)
$user->setNamespace('other');

Assert::false( $user->isLoggedIn(), 'isLoggedIn?' );
Assert::null( $user->getIdentity(), 'getIdentity' );
