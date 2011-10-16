<h3><?=lang('merge_module_name')?></h3>
<?php

echo form_open($action_url);

$this->table->set_template($cp_pad_table_template);
$this->table->set_heading(
    array('data' => lang('preference'), 'style' => 'width:50%;'),
    lang('setting')
);

$this->table->add_row(array(
		'colspan' 	=> 2,
		'data'		=> lang('details')
	)
); 

$this->table->add_row(array(
		lang('html_root'),
		form_input(array('id'=>'merge_html_root', 'name'=>'merge_html_root', 'class'=>'field','value'=>$merge_html_root))
	)
);

$this->table->add_row(array(
		lang('cache_path'),
		form_input(array('id'=>'merge_cache_path', 'name'=>'merge_cache_path', 'class'=>'field','value'=>$merge_cache_path))
	)
);

$this->table->add_row(array(
		lang('cache_web_path'),
		form_input(array('id'=>'merge_cache_web_path', 'name'=>'merge_cache_web_path', 'class'=>'field','value'=>$merge_cache_web_path))
	)
);


echo $this->table->generate();

?>

	<?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'))?>

<?=form_close()?>
