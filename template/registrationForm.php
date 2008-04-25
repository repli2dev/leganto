<form method="post" action="<?php echo $action ?>">
  <fieldset>
    <legend><?php echo $lng->registration ?></legend>
    <p><input type="text" name="name" /><label><?php echo $lng->name ?></label></p>
    <p><input type="password" name="pswd" /><label><?php echo $lng->pswd ?></label></p>
    <p><input type="password" name="pswdCtrl" /><label><?php echo $lng->pswdCtrl ?></label></p>
    <p><input type="text" name="email" /><label><?php echo $lng->email ?></label></p>
    <p>
     <textarea name="description"><?php echo $lng->userDescription ?></textarea>
    </p>
    <p><input type="submit" name="registration" value="<?php echo $lng->registrate ?>" /></p>
  </fieldset>
</form>
