<?php head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); ?>

<h1>Dropbox Plugin</h1>

<div id="primary">
    <p>You've successfully uploaded the following file(s):</p>
    <ul>
    <?php foreach ($fileNames as $fileName): ?>
    	<li><?php echo html_escape($fileName); ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php foot(); ?>