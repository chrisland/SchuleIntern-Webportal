<?php if ( !defined('CMS_EXEC') ) { exit; } ?>

<?php

// echo '<pre>';
// print_r($this);
// echo '</pre>';	

?>



<h1>ADMIN Dashboard</h1>
<section class="articles">
	<?php if ( $data = $this->get('data') ): ?>

		<h2>data:</h2>
		<?php
			echo '<pre>';
			//print_r( $data );
			echo '</pre>';	
		?>
	<?php else: ?>

		No Entry!

	<?php endif; ?>
</section>


<div id="app"></div>
<?= $script ?>