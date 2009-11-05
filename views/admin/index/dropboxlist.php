<h2>Select Files From Dropbox</h2>

<?php
    $filePath = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Dropbox' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
    $fileNames = dropbox_dir_list($filePath);
?>

<?php if ($fileNames == NULL): ?>
    <p>No files have been uploaded to the dropbox.</p>
<?php else: ?>
    <script type="text/javascript" charset="utf-8">
        
        function dropboxSelectAllCheckboxes(checked) {
    		$$('#dropbox-file-checkboxes input').each(function(i) {
    			if (i.parentNode.visible()) {
    			    i.checked = checked;
    			}
    		});
    	}
    	
    	function dropboxFilterFiles() {
    	    var filter = $('dropbox-file-filter').value.toLowerCase();
    	    $$('#dropbox-file-checkboxes input').each(function(i) {
    			if (filter != '') {
                    $('dropbox-show-all-span').show();
                    if (i.value.toLowerCase().match(filter)) {
                        i.parentNode.show();
                    } else {
                        i.parentNode.hide();
                    }
    			} else {
    			    i.parentNode.show();
    			    $('dropbox-show-all-span').hide();
    			}
    		});
    	}

		function dropboxNoEnter(e) {
            var e  = (e) ? e : ((event) ? event : null);
            var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
            if ((e.keyCode == 13) && (node.type=="text")) {return false;}
        }

    	Event.observe(window,'load',function() {

    		// Select all the checkboxes
    		Event.observe($('dropbox-select-all'),'click',function(e) {
    		    e.stop();
    			dropboxSelectAllCheckboxes(true);
    			return;
    		});

            // Unselect the checkboxes
    		Event.observe('dropbox-unselect-all','click',function(e) {
    		    e.stop();
    			dropboxSelectAllCheckboxes(false);
    			return;
    		});
    		
    		// Show all the checkboxes
    		Event.observe('dropbox-show-all','click',function(e) {
    		    e.stop();
    			$('dropbox-file-filter').value = '';
    			dropboxFilterFiles();
    			return;
    		});
    		
    		Event.observe('dropbox-file-filter','keyup', function(e) {
    		    e.stop();
    		    dropboxFilterFiles();
    		    return;
    		});
        		
            document.getElementById('dropbox-file-filter').onkeypress = dropboxNoEnter;
    	    $('dropbox-show-all-span').hide();		
    	});
    </script>
    <p>To filter the files, enter part of the filename below:</p>
    <p><input id="dropbox-file-filter" name="dropbox-file-filter" value="" /></p>
    <p>Select the files you wish to upload.</p>
    <p><a id="dropbox-select-all" href="#">Select All</a> / <a id="dropbox-unselect-all" href="#">Unselect All</a><span id="dropbox-show-all-span"> / <a id="dropbox-show-all" href="#">Show All</a></span></p>
    <ul id="dropbox-file-checkboxes">
        <?php foreach ($fileNames as $fileName): ?>
        <li><input type="checkbox" name="dropbox-files[]" value="<?php echo html_escape($fileName); ?>"/><?php echo html_escape($fileName); ?></li>
        <?php endforeach; ?>
    </ul>
   
<?php endif; ?>