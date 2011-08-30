<?php
    $filePath = PLUGIN_DIR . DIRECTORY_SEPARATOR . 'Dropbox' . DIRECTORY_SEPARATOR . 'files';
    $fileNames = dropbox_dir_list($filePath);
?>

<?php if (!$fileNames): ?>
    <p>No files have been uploaded to the dropbox.</p>
<?php else: ?>
    <script type="text/javascript">
        function dropboxSelectAllCheckboxes(checked) {
            jQuery('#dropbox-file-checkboxes tr:visible input').each(function() {
                this.checked = checked;
            });
        }

        function dropboxFilterFiles() {
            var filter = jQuery.trim(jQuery('#dropbox-file-filter').val().toLowerCase());
            var someHidden = false;
            jQuery('#dropbox-file-checkboxes input').each(function() {
                var v = jQuery(this);
                if (filter != '') {
                    if (v.val().toLowerCase().match(filter)) {
                        v.parent().parent().show();
                    } else {
                        v.parent().parent().hide();
                        someHidden = true;
                    }
                } else {
                    v.parent().parent().show();
                }
            });

            jQuery('#dropbox-show-all').toggle(someHidden);
        }

        function dropboxNoEnter(e) {
            var e  = (e) ? e : ((event) ? event : null);
            var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
            if ((e.keyCode == 13) && (node.type=="text")) {return false;}
        }

        jQuery(document).ready(function () {
            jQuery('#dropbox-select-all').click(function () {
                dropboxSelectAllCheckboxes(this.checked);
            });

            jQuery('#dropbox-show-all').click(function () {
                jQuery('#dropbox-file-filter').val('');
                dropboxFilterFiles();
                return false;
            });

            jQuery('#dropbox-file-filter').keyup(function () {
                dropboxFilterFiles();
                return false;
            }).keypress(dropboxNoEnter);

            jQuery('.dropbox-js').show();
            jQuery('#dropbox-show-all').hide();
        });
    </script>
    
    <p class="dropbox-js" style="display:none; vertical-align:baseline; margin-bottom:0">
        Filter files by name:
        <input id="dropbox-file-filter" name="dropbox-file-filter" class="textinput" style="font-size:1em">
        <a href="#" id="dropbox-show-all" style="vertical-align:baseline">Show All</a>
    </p>
    <table>
        <colgroup>
            <col style="width: 2em">
        </colgroup>
        <thead>
            <tr>
                <th><input type="checkbox" id="dropbox-select-all" class="dropbox-js" style="display:none"></th>
                <th>File Name</th>
            </tr>
        </thead>
        <tbody id="dropbox-file-checkboxes">
        <?php foreach ($fileNames as $fileName): ?>
            <tr><td><input type="checkbox" name="dropbox-files[]" value="<?php echo html_escape($fileName); ?>"/></td><td><?php echo html_escape($fileName); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif;
