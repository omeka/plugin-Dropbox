<?php head(array('title' => 'Dropbox')); ?>
<?php common('archive-nav'); ?>
<div id="primary">

	<p>You've successfully batch uploaded the following files:</p>

<table>
<?php 	
$files = $_POST['file'];

foreach ($files as $originalName) {
	?>
	<tr><td></td><td><h2><?php echo $originalName; ?></h2></td></tr>
<?php 
	}
?>
</table>
</div>
<?php foot(); ?>