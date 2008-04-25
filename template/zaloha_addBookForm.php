<form method="post" action="<?php echo $action ?>">
  <fieldset>
    <legend><?php echo $lng->addBook ?></legend>
    <p><input type="text" name="title" value="<?php echo $bookTitle ?>" /><label><?php echo $lng->bookTitle ?></p>
    <p><input type="text" name="writerNameFirst" value="<?php echo $writerNameFirst ?>" /><label><?php echo $lng->writerNameFirst ?></p>
    <p><input type="text" name="writerNameSecond" value="<?php echo $writerNameSecond ?>" /><label><?php echo $lng->writerNameSecond ?></p>
    <p><input type="text" name="tag" /><label><?php echo "$lng->tags ($lng->tagInfo)" ?></p>
    <p>
      <select name="rating">
        <option value="1"><?php echo $lng->rating1 ?></option>
        <option value="2"><?php echo $lng->rating2 ?></option>
        <option value="3"><?php echo $lng->rating3 ?></option>
        <option value="4"><?php echo $lng->rating4 ?></option>
        <option value="5"><?php echo $lng->rating5 ?></option>
      </select>
      <label><?php echo $lng->rating ?></p>
    </p>
    <p><textarea name="opinion" rows="15"><?php echo $lng->opinion ?></textarea></p>
    <p><input type="submit" name="addBook" value="<?php echo $lng->addBook ?>" /></p>
  </fieldset>
</form>
