<?php head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); ?>

<h1>Dropbox Plugin</h1>

<div id="primary">
    <p>You've successfully batch uploaded the following file(s):</p>
    <ul>
    <?php foreach ($files as $originalName): ?>
    	<li><?php echo html_escape($originalName); ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?php foot(); ?>