<?php head(array('title' => 'Dropbox', 'bodyclass' => 'dropbox')); ?>

<h1>Dropbox Plugin</h1>

<div id="primary">
	<?php echo flash(); ?>
	
	<p>To batch upload files, upload them to the /files/ folder of the Dropbox plugin.  After refreshing this page, they should appear listed below.</p>    
    
    <form action="<?php echo html_escape(uri(array('action'=>'add'))); ?>" method="post" accept-charset="utf-8">

        <?php include 'dropboxlist.php'; ?>



	    <fieldset>
    			<legend>Set Properties for Batch Upload</legend>
    			<p>Any properties set will be applied to each file uploaded - to customize properties, edit individual items after creating them.</p>
    			<div class="field">
    				<div class="label">Item is public:</div> 
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
    				<div class="label">Item is featured:</div> 
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
            <?php echo label('collection-id', 'Collection');?>
            <div class="inputs">
            	<?php echo select_collection(array('name'=>'collection_id', 'id'=>'collection-id'),$item->collection_id); ?>
            </div>
            </div>
    	</fieldset>

    	<fieldset>
    		<legend>Tagging</legend>

    		<p>Separate tags with commas (lorem,ipsum,dolor sit,amet).</p>
    		<div class="field">
    			<label for="tags-field">Your Tags</label>
    			<input type="text" name="tags" id="tags-field" class="textinput" />
    		</div>
    	</fieldset>
    	
	    <fieldset>
    		<input type="submit" name="submit" id="add_items" value="Upload Files / Items" />
    	</fieldset>
	    
	    
	</form>

	<p>Further information about using and installing the Dropbox plugin can be found on the <a href="http://omeka.org/codex/dropbox_plugin">Omeka Codex</a></p>

</div>

<?php foot(); ?>