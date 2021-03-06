<?php if(!defined('__ZBXE__')) exit();
$output->query_id = "module.updateSite";
$output->action = "update";
if(isset($args->site_srl)) { unset($_output); $_output = $this->checkFilter("site_srl",$args->site_srl,"number"); if(!$_output->toBool()) return $_output; } 
if(!isset($args->site_srl)) return new Object(-1, sprintf($lang->filter->isnull, $lang->site_srl?$lang->site_srl:'site_srl'));
$output->column_type["site_srl"] = "number";
$output->column_type["index_module_srl"] = "number";
$output->column_type["domain"] = "varchar";
$output->column_type["default_language"] = "varchar";
$output->column_type["regdate"] = "date";
$output->tables = array( "sites"=>"sites", );
$output->_tables = array( "sites"=>"sites", );
$output->columns = array ( array("name"=>"index_module_srl","alias"=>"","value"=>$args->index_module_srl),
array("name"=>"domain","alias"=>"","value"=>$args->domain),
array("name"=>"default_language","alias"=>"","value"=>$args->default_language),
 );
$output->conditions = array ( array("pipe"=>"",
"condition"=>array(array("column"=>"site_srl", "value"=>$args->site_srl,"pipe"=>"","operation"=>"equal",),
)),
 );
return $output; ?>