<?php 
    queue_js_file('items');
    queue_js_file('tabs');
    echo head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); 
    $tagDelimiter = get_option('tag_delimiter');
  ?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function () {
    Omeka.Items.tagDelimiter = <?php echo js_escape($tagDelimiter); ?>;
    Omeka.Items.tagChoices('#dropbox-tags', <?php echo js_escape(url(array('controller' => 'tags', 'action' => 'autocomplete'), 'default', array(), true)); ?>);
});
//]]>
</script>

<h1>Dropbox</h1>

<div id="primary">
    <?php echo flash(); ?>

    <p>
        To add files to the Dropbox, upload them to the Dropbox plugin's
        /files/ folder.  Files in the Dropbox can be added to the
        archive in bulk from this page, or added to individual items
        from the normal item interface.
    </p>

    <form action="<?php echo html_escape(url(array('action'=>'add'))); ?>" method="post" accept-charset="utf-8">

        <h2>Batch Add Files</h2>
        <p>For each file selected, a new item will be created. The properties set below will be applied to each new item.</p>
        <h3>Select Files from Dropbox</h3>
        <?php dropbox_list(); ?>
        <fieldset>
            <h3>Item Properties</h3>
            
            <div class="field">
                <label for="dropbox-public">Public</label>
                <div class="inputs">
                    <?php echo $this->formCheckbox('dropbox-public', null, array('checked' => true)); ?>
                </div>
            </div>
            <div class="field">
                <label for="dropbox-featured">Featured</label>
                <div class="inputs">
                    <?php echo $this->formCheckbox('dropbox-featured'); ?>
                </div>
            </div>
            <div class="field">
                <label for="dropbox-collection-id">Collection</label>
                <div class="inputs">
                    <?php  
                          
                         echo $this->formSelect(
                                    'dropbox-collection-id',
                                     (!isset($item))? null : $item->collection_id,
                                    array('id'=>'collection_id'),
                                    get_table_options('Collection')
                                 );
                    ?>
                    
                </div>
            </div>
            <div class="field">
                <label for="dropbox-tags">Tags</label>
                <div class="inputs">
                    <?php echo $this->formText('dropbox-tags', null, array('class' => 'textinput')); ?>
                    <p class="explanation">Separate tags with <?php echo get_option('tag_delimiter'); ?></p>
                </div>
            </div>
        </fieldset>

        <div class="input">
            <input type="submit" class="submit" name="submit" id="dropbox-upload-files" value="Upload Files as Items" />
        </div>
    </form>
</div>

<?php echo foot();
