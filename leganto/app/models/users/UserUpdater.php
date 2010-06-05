<?php
/**
 * The source file is subject to the license located on web
 * "http://code.google.com/p/preader/".
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @link		http://code.google.com/p/preader/
 * @license		http://code.google.com/p/preader/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
 
class UserUpdater implements IUpdater {

	const ERROR_OLD_HASH = 100;

	const ERROR_WRONG_HASH = 101;

	/* PUBLIC METHODS */
	
	public function update(IEntity $entity){
		return SimpleUpdater::createUpdater("user")->update($entity);
	}

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
}
?>
