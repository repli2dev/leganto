<?php
/**
 * It checks e-mail validity.
 *
 * @author Jan Papousek
 */
class EmailFilter implements IFilter
{

	/**
	 * It checks e-mail validity.
	 * 
	 * @param string $email
	 * @return boolean
	 * @throws NullPointerException if the $email is empty.
	 */
	public function accepts($email) {
		if (empty($email)) {
			throw new NullPointerException("email");
		}
        // The characters which create username
        $atom = "[-a-z0-9!#$%&\'*+/=?^_`{|}~]";
        // A part of domain
        $domain = "[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])";
        return (eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email));
	}

}
?>
