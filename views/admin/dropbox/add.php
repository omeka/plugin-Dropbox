<?php head(); ?>

<h1>Dropbox Plugin</h1>

<div id="primary">

<?php if ($files = $_POST['file']): ?>
	<p>You've successfully batch uploaded the following file(s):</p>
<ul>
<?php 	
foreach ($files as $originalName) {
	?>
	<li><?php echo $originalName; ?></li>
<?php } ?>
</ul>
<?php else: ?>
    <h4>You must select a file to upload.  Please return and try again.</h4>
<?php endif; ?>
</div>
<?php foot(); ?>