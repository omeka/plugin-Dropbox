<?php head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); ?>

<h1>Dropbox</h1>

<div id="primary">
    <?php if ($notUploadedFileNamesToErrorMessages): ?>
        <div id="dropbox_not_uploaded_filenames">
            <p>The following file(s) could NOT be uploaded:</p>
            <ul>
            <?php foreach ($notUploadedFileNamesToErrorMessages as $fileName=>$errorMessage): ?>
                <li><?php echo html_escape($fileName); ?><br/><?php echo html_escape($errorMessage);?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ($uploadedFileNames): ?>
        <div id="dropbox_not_uploaded_filenames">
            <p>The following file(s) were successfully uploaded:</p>
            <ul>
            <?php foreach ($uploadedFileNames as $fileName): ?>
                <li><?php echo html_escape($fileName); ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<?php foot();
