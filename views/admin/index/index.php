<?php head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); ?>

<?php echo js('items'); ?>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function () {
    Omeka.Items.tagChoices('#dropbox-tags', <?php echo js_escape(uri(array('controller' => 'tags', 'action' => 'autocomplete'), 'default', array(), true)); ?>);
});
//]]>     
</script>

<h1>Dropbox</h1>

<div id="primary">
	<?php echo flash(); ?>
	
	<p>To batch upload files, upload them to the /files/ folder of the Dropbox plugin.  After refreshing this page, they should appear listed below.</p>    
    
    <form action="<?php echo html_escape(uri(array('action'=>'add'))); ?>" method="post" accept-charset="utf-8">

        <?php include 'dropboxlist.php'; ?>

	    <fieldset>
    			<legend>Set Properties for Batch Upload</legend>
    			<p>Any properties set will be applied to each file uploaded - to customize properties, edit individual items after creating them.</p>
    			<div class="field">
    				<div class="label">Item is Public:</div> 
    				<div class="radio">
    				    <label class="radiolabel">
    				        <input type="radio" name="dropbox-public" id="dropbox-public-no" value="0" />No
    				    </label>
    				    <label class="radiolabel">
    				        <input type="radio" name="dropbox-public" id="dropbox-public-yes" value="1"  checked="checked"/>Yes
    				    </label>
    				</div>
    			</div>
    			<div class="field">
    				<div class="label">Item is Featured:</div> 
    				<div class="radio">
    				    <label class="radiolabel">
    				        <input type="radio" name="dropbox-featured" id="dropbox-featured-no" value="0" checked="checked" />No
    				    </label>
    				    <label class="radiolabel">
    				        <input type="radio" name="dropbox-featured" id="dropbox-featured-yes" value="1" />Yes
    				    </label>
    				</div>
    			</div>
    	</fieldset>

	    <fieldset id="collection-metadata">
    		<legend>Collection Metadata</legend>
            <div class="field">
            <?php echo label('dropbox-collection-id', 'Collection');?>
            <div class="inputs">
                <?php echo select_collection(array('name'=>'dropbox-collection-id', 'id'=>'dropbox-collection-id')); ?>
            </div>
            </div>
    	</fieldset>

    	<fieldset>
    		<legend>Tagging</legend>
    		<p>Separate tags with commas (lorem,ipsum,dolor sit,amet).</p>
    		<div class="input">
    			<label for="dropbox-tags">Your Tags</label>
    			<input type="text" name="dropbox-tags" id="dropbox-tags" class="textinput" />
    		</div>
    	</fieldset>
    	
        <div class="input">
    		<input type="submit" class="submit" name="submit" id="dropbox-upload-files" value="Upload Files as Items" />
        </div>
	</form>
</div>

<?php foot();
