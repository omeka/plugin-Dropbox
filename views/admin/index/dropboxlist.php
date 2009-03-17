				<h2>Select Files From Dropbox</h2>


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
		$isdir = is_dir($file);
        if (($file != '.') && ($file != '..') && ($file != '.svn') && ($isdir != '1'))
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
    echo "<p>No files have been uploaded to the dropbox.</p>";
} else {
?>
    <script type="text/javascript" charset="utf-8">
        function allCheckboxes(checked) {
    		$$('#file_checkboxes input').each(function(i) {
    			i.checked = checked;
    		});
    	}

    	Event.observe(window,'load',function() {

    		//Select all the checkboxes
    		Event.observe($('select-all'),'click',function(e) {
    		    e.stop();
    			allCheckboxes(true);
    			return;
    		});

    		Event.observe('select-none','click',function(e) {
    		    e.stop();
    			allCheckboxes(false);
    			return;
    		});
    	});
    </script>
    <p>Select the individual files you wish to upload, or <a id="select-all" href="#">select all</a> / <a id="select-none" href="#">unselect all</a></p>
    <ul id="file_checkboxes">
<?php
    foreach ($results as $result) {
        echo '<li><input type="checkbox" name="file[]" value="' . $result .'">' . $result . '</li>';
    }
    echo '</ul>';
}
?>