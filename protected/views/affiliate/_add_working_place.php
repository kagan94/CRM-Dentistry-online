

<div class="row new-place" id="place_n<?php echo $index; ?>">
	<?php echo CHtml::activeLabel($model,'title'); ?>
	<?php echo CHtml::activeTextField($model, "title[$index]", array('maxlength'=>255)); ?>
	<button type="button" class="del-button" id="del_<?php echo $index; ?>" onclick="deleteRow(this.id); return false;">Удалить</button>
	<?php echo CHtml::error($model,'working_places'); ?>
</div>

