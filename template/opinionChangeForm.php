<form method="post" action="<?php echo $action ?>">
  <fieldset>
    <legend><?php echo $lng->changeOpinion ?></legend>
    <p><input type="text" name="title" value="<?php echo $op->bookTitle ?>" disabled /><label><?php echo $lng->bookTitle ?></p>
    <p><input type="text" name="writerName" value="<?php echo $op->writerName ?>" disabled /><label><?php echo $lng->writer ?></p>
    <p>
      <select name="rating">
        <option value="1"<?php if ($op->rating == 1) echo " selected "?>><?php echo $lng->rating1 ?></option>
        <option value="2"<?php if ($op->rating == 2) echo " selected "?>><?php echo $lng->rating2 ?></option>
        <option value="3"<?php if ($op->rating == 3) echo " selected "?>><?php echo $lng->rating3 ?></option>
        <option value="4"<?php if ($op->rating == 4) echo " selected "?>><?php echo $lng->rating4 ?></option>
        <option value="5"<?php if ($op->rating == 5) echo " selected "?>><?php echo $lng->rating5 ?></option>
      </select>
      <label><?php echo $lng->rating ?></p>
    </p>
    <p><textarea name="opinion" rows="30"><?php echo $op->content ?></textarea></p>
    <p><input type="submit" name="changeOpinion" value="<?php echo $lng->changeOpinion ?>" /></p>
  </fieldset>
</form>