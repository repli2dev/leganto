<div>
	<a href="<?php echo $url ?>" title="<?php echo $lng->sendMessage ?>" id="messageSendLink">
		<?php
			echo $lng->messages." (";
			$mes = new message;
			$count = $mes->countNotRead();
			if (!$count) $count = 0;
			echo $count.")";
		?>
	</a>
</div> 