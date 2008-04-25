<form method="post" action="<?php echo $action ?>"  onClick="AutoSuggestDeactive();">
  <fieldset>
    <legend><?php echo $lng->addBook ?></legend>
     <p><input type="text" name="title" value="<?php echo $bookTitle ?>" id="bookTitle" onKeyUp="AutoSuggestBook.CreateResults(event);" /><label><?php echo $lng->bookTitle ?></p>
	  <select id="SuggestResultsBook" multiple>
	  </select>
<script language="JavaScript">
	window.AutoSuggestBook = new AutoSuggestBox('bookTitle', 'SuggestResultsBook', GetResultsBook, HandleChoice);
</script>
     <p><input type="text" name="writerNameFirst" id="writerNameFirst" value="<?php echo $writerNameFirst ?>" onKeyUp="AutoSuggestWriterFirst.CreateResults(event);" /><label><?php echo $lng->writerNameFirst ?></p>
	  <select id="SuggestResultsWriterFirst" multiple>
	  </select>
<script language="JavaScript">
	window.AutoSuggestWriterFirst = new AutoSuggestBox('writerNameFirst', 'SuggestResultsWriterFirst', GetResultsWriterFirst, HandleChoice);
</script>
    <p><input type="text" name="writerNameSecond" id="writerNameSecond" value="<?php echo $writerNameSecond ?>" onKeyUp="AutoSuggestWriterSecond.CreateResults(event);" /><label><?php echo $lng->writerNameSecond ?></p>
	  <select id="SuggestResultsWriterSecond" multiple>
	  </select>
<script language="JavaScript">
	window.AutoSuggestWriterSecond = new AutoSuggestBox('writerNameSecond', 'SuggestResultsWriterSecond', GetResultsWriterSecond, HandleChoice);
</script>
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
