<form action="" method="post" enctype="multipart/form-data">
	<fieldset>	
    	<p>
    		<input type="file" name="image" />
    	</p><p>
			<input type="submit" name = "load" value="Load" />    	
    	</p>
	</fieldset>
</form>
<?
if ($_POST["load"]) {
	Piko::setDirectory("./");
	Piko::work($_FILES["image"]);
}
?>
