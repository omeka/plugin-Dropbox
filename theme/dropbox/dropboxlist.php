<form action="<?php echo uri('dropbox/add') ?>" name="form" method="post" accept-charset="utf-8">

<?php

function dirList($directory) 
{

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // keep going until all files in directory have been read
    while ($file = readdir($handler)) {

        // if $file isn't this directory or its parent, 
        // add it to the results array
        if ($file != '.' && $file != '..')
            $results[] = $file;
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    return $results;

}

$filepath = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Dropbox' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;

 $results = dirList($filepath);

  if ($results == NULL) {
	echo "<p>no files have been uploaded to the dropbox</p>";
}
 else {
	echo '<ul id="file_checkboxes">';
foreach ($results as $result) {
	echo '<li><input type="checkbox" name="file[]" value="' . $result .'">' . $result . '</li>';
}
	echo '</ul>';
}
?>
