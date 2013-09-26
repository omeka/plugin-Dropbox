<?php $fileNames = dropbox_dir_list(dropbox_get_files_dir_path()); ?>
<?php if (!$fileNames): ?>
    <p><strong>No files have been uploaded to the dropbox.</strong></p>
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
    
    <p class="dropbox-js" style="display:none;">
        Filter files by name:
        <input type="text" id="dropbox-file-filter">
        <a href="#" id="dropbox-show-all">Show All</a>
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
