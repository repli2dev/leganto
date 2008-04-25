  <body>
		<div id="container">
			<div id="header">
				<div id="logo"><h1><?php echo $this->webName ?></h1></div>
				<div id="menu">
					<ul>
   					<li><a href="index.php" title="<?php echo $lng->introduction ?>"><?php echo $lng->introduction ?></a></li>

<?php
$user = new user;
switch ($this->owner->level) {
 default:
?>
						<li><a href="user.php?action=regForm" title="<?php echo $lng->registrate ?>"><?php echo $lng->registrate ?></a></li>
<?php
 break;
 case $user->levelAdmin:
?>
						<li><a href="admin.php" title="<?php echo $lng->admin ?>"><?php echo $lng->admin ?></a></li>
<?php
 case $user->levelModerator:
?>
						<li><a href="moderator.php" title="<?php echo $lng->moderator ?>"><?php echo $lng->moderator ?></a></li>
<?php
 case $user->levelCommon:
?>
          			<li><a href="opinionAction.php?action=addForm" title="<?php echo $lng->addBook ?>"><?php echo $lng->addBook ?></a></li>
<?php
}
?>
						<li><a href="user.php?action=list" title="<?php echo $lng->users ?>"><?php echo $lng->users ?></a></li>
						<li><a href="discussion.php" title="<?php echo $lng->discussion ?>"><?php echo $lng->discussion ?></a></li>
   					<li><a href="about.php" title="<?php echo $lng->projectInfo ?>"><?php echo $lng->projectInfo ?></a></li>  	
    				</ul>
        	   	<form id="search" action="search.php">
  		   	  	 <fieldset>						<p>							
							<input type="text" name="searchWord" />
             			<select name="column">
             				<option value="all"><?php echo $lng->all ?></option>
              				<option value="bookTitle"><?php echo $lng->bookTitle ?></option>
              				<option value="writer"><?php echo $lng->writer ?></option>
              				<option value="tag"><?php echo $lng->tag ?></option>
             			</select>
             			<input type="submit" class="submit" value="<?php echo $lng->search ?>" />
             		</p>
         		 </fieldset>
        			</form>
				</div> 
			</div>      		
			<div id="middle">
				<div id="content">