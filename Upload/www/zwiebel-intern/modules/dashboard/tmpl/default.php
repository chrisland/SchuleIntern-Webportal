<?php if ( !defined('CMS_EXEC') ) { exit; } ?>

<?php

// echo '<pre>';
// print_r($this);
// echo '</pre>';	

?>



<h1>Dashboard</h1>
<section class="articles">
	<?php if ( $data = $this->get('data') ): ?>

		<h2>data:</h2>
		<?php
			echo '<pre>';
			print_r( $data );
			echo '</pre>';	
		?>
	<?php else: ?>

		No Entry!

	<?php endif; ?>
</section>



<div id="app">
	<app inline-template>
		<div>
			{{items}}-{{val}}
			<span>a</span>
			<span ref="b">b</span>
			<span>c</span>
			<input v-model="val" type="text" />
			<button @click="handlerClick">Click</button>
		</div>
	</app>
	</div>
	

	<script type="text/html" id="my-comp-template">
  <div>zahl:{{ count }}</div>
</script>



	<?= $script ?>
