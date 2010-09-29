<?php
/**
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (me@jandrabek.cz)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$id$
 */
 
class UserUpdater implements IUpdater {

	const ERROR_OLD_HASH = 100;

	const ERROR_WRONG_HASH = 101;

	/* PUBLIC METHODS */
	
	public function update(IEntity $entity){
		return SimpleUpdater::createUpdater("user")->update($entity);
	}

	/**
	 * Create new pass and store it
	 * @param UserEntity $entity
	 * @return string
	 */
	public function generateHashForNewPassword(UserEntity $entity){
		$hash = ExtraString::random(30);
		dibi::update("user",
			array(
				"new_pass_key" => $hash,
				"new_pass_time" => new DateTime()
			)
		)
			->where("id_user = %i",$entity->id)
			->execute();
		return $hash;
	}

	/**
	 * Function for confirming and changing users password
	 * @param UserEntity $entity
	 * @param string $hash pass code
	 * @return string new password
	 */
	public function confirmNewPassword(UserEntity $entity,$hash){
		$row = SimpleTableModel::createTableModel("user")->find($entity->getId());
		if(!empty($row->new_pass_time) && strtotime($row->new_pass_time) > (time()-24*60*60)){
			if($row->new_pass_key == $hash){
				$newPassword = ExtraString::random(10);
				dibi::update("user",
					array(
						"new_pass_key" => "",
						"new_pass_time" => "NULL",
						"password" => UserAuthenticator::passwordHash($newPassword)
					)
				)
					->where("id_user = %i",$entity->id)
					->execute();
				return $newPassword;
			} else {
				throw new InvalidStateException("The hash is wrong.",self::ERROR_WRONG_HASH);
			}
		} else {
			throw new InvalidStateException("The hash is too old.",self::ERROR_OLD_HASH);
		}
	}

	/**
	 * Remove pass code from from user given by user entity (e.g. after successful login)
	 * @param UserEntity $entity
	 */
	public function removePassCode(UserEntity $entity){
		if($entity->new_pass_key){
			dibi::update("user",
				array(
					'new_pass_key' => '',
					'new_pass_time' => 'NULL'
				)
			)
				->where("id_user = %i",$entity->id)
				->execute();
		}
	}
	/**
	 * Remove invalid pass codes (those which expired)
	 */
	public function removeOldPassCodes(){
		dibi::update("user",
			array(
				'new_pass_key' => '',
				'new_pass_time' => 'NULL'
			)
		)
			->where("UNIX_TIMESTAMP(new_pass_time) < %i",time()-24*60*60)
			->execute();
	}

	/**
	 * Toogle follow/unfollow of given user and current logged user
	 * @param int $user id of user
	 * @return Boolean
	 */
	public function toogleFollow($user) {
		if(Leganto::users()->getSelector()->isFollowedBy($user,System::user())) {
			dibi::delete("following")
				->where("id_user = %i",System::user()->getId())
				->where("id_user_followed = %i",$user)
				->execute();
			return FALSE;
		} else {
			dibi::insert("following",
				array(
				    'id_user' => System::user()->getId(),
				    'id_user_followed' => $user
				)
			)->execute();
			return TRUE;
		}
	}
}
?>
