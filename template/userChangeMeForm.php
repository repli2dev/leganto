<form method="post" action="<?php echo $action ?>">
  <fieldset>
    <legend><?php echo $lng->changeUserInfo ?></legend>
    <p><input type="password" name="pswd" /><label><?php echo $lng->pswd ?></label></p>
    <p><input type="password" name="pswdCtrl" /><label><?php echo $lng->pswdCtrl ?></label></p>
    <p><input type="text" name="email" value="<?php echo $user->email ?>" /><label><?php echo $lng->email ?></label></p>
    <p>
     <textarea name="description"><?php echo $user->description ?></textarea>
    </p>
    <p><input type="submit" name="userChange" value="<?php echo $lng->changeUserInfo ?>" /></p>
  </fieldset>
</form>
