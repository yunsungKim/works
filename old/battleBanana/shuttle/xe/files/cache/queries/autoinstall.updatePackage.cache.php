<?php if(!defined('__ZBXE__')) exit();
$output->query_id = "autoinstall.updatePackage";
$output->action = "update";
if(!isset($args->package_srl)) return new Object(-1, sprintf($lang->filter->isnull, $lang->package_srl?$lang->package_srl:'package_srl'));
$output->column_type["package_srl"] = "number";
$output->column_type["category_srl"] = "number";
$output->column_type["path"] = "varchar";
$output->column_type["updatedate"] = "date";
$output->column_type["latest_item_srl"] = "number";
$output->column_type["version"] = "varchar";
$output->tables = array( "autoinstall_packages"=>"autoinstall_packages", );
$output->_tables = array( "autoinstall_packages"=>"autoinstall_packages", );
$output->columns = array ( array("name"=>"path","alias"=>"","value"=>$args->path),
array("name"=>"updatedate","alias"=>"","value"=>$args->updatedate),
array("name"=>"category_srl","alias"=>"","value"=>$args->category_srl),
array("name"=>"latest_item_srl","alias"=>"","value"=>$args->latest_item_srl),
array("name"=>"version","alias"=>"","value"=>$args->version),
 );
$output->conditions = array ( array("pipe"=>"",
"condition"=>array(array("column"=>"package_srl", "value"=>$args->package_srl,"pipe"=>"","operation"=>"equal",),
)),
 );
return $output; ?>