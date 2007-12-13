<?php head(array('title' => 'Dropbox', 'body_class' => 'archive-plugin')); ?>
<?php common('archive-nav'); ?>
<script type="text/javascript" charset="utf-8">
    function allCheckboxes(checked) {
		$$('#file_checkboxes input').each(function(i) {
			i.checked = checked;
		});
	}

	Event.observe(window,'load',function() {

		//Select all the checkboxes
		Event.observe($('select-all'),'click',function() {
			allCheckboxes(true);
			return;
		});

		Event.observe('select-none','click',function() {
			allCheckboxes(false);
			return;
		});
	});

</script>
<div id="primary">
	<p>To batch upload files, upload them to the /files/ folder of the Dropbox plugin.  After refreshing this page, they should appear listed below.</p>
	
	<fieldset>
	<legend>Select Files From Dropbox</legend>
	<p>Select the individual files you wish to upload, or <a id="select-all" href="#">select all</a> / <a id="select-none" href="#">unselect all</a></p>
	
<?php include 'dropboxlist.php'; ?>

<?php

//echo PLUGIN_DIR;

?>

	</fieldset>

	<fieldset>
			<legend>Set Properties for Batch Upload</legend>
			<p>Any properties set will be applied to each file uploaded - to customize properties, edit individual items after creating them.
			<div class="field">
				<div class="label">Item is public:</div> 
				<div class="radio"><label class="radiolabel"><input type="radio" name="public" id="public" value="0" />No</label><label class="radiolabel"><input type="radio" name="public" id="public" value="1"  checked="checked"/>Yes</label></div>
			</div>
			<div class="field">
				<div class="label">Item is featured:</div> 
				<div class="radio"><label class="radiolabel"><input type="radio" name="featured" id="featured" value="0" checked="checked" />No</label><label class="radiolabel"><input type="radio" name="featured" id="featured" value="1" />Yes</label></div>
	</fieldset>

	<fieldset id="collection-metadata">
		<legend>Collection Metadata</legend>
		<div class="field">
		<?php select('collection_id',
					collections(),
					$item->collection_id,
					'Collection',
					'id',
					'name' ); ?>
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

</div>

<?php foot(); ?>