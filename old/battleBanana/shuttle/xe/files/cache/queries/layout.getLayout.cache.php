<?php if(!defined('__ZBXE__')) exit();
$output->query_id = "layout.getLayout";
$output->action = "select";
if(isset($args->layout_srl)) { unset($_output); $_output = $this->checkFilter("layout_srl",$args->layout_srl,"number"); if(!$_output->toBool()) return $_output; } 
if(!isset($args->layout_srl)) return new Object(-1, sprintf($lang->filter->isnull, $lang->layout_srl?$lang->layout_srl:'layout_srl'));
$output->column_type["layout_srl"] = "number";
$output->column_type["site_srl"] = "number";
$output->column_type["layout"] = "varchar";
$output->column_type["title"] = "varchar";
$output->column_type["extra_vars"] = "text";
$output->column_type["layout_path"] = "varchar";
$output->column_type["module_srl"] = "number";
$output->column_type["regdate"] = "date";
$output->column_type["layout_type"] = "char";
$output->tables = array( "layouts"=>"layouts", );
$output->_tables = array( "layouts"=>"layouts", );
$output->columns = array ( array("name"=>"*","alias"=>""),
 );
$output->conditions = array ( array("pipe"=>"",
"condition"=>array(array("column"=>"layout_srl", "value"=>$args->layout_srl,"pipe"=>"","operation"=>"equal",),
)),
 );
return $output; ?>