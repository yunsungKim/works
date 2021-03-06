.colorpicker img, .colorpicker div {
	behavior: url(iepngfix.htc);
}


.xe_colorpicker {
	position:absolute;
	width:194px;
	height:183px;
	overflow:hidden;
	z-index:100;
}

.xe_colorpicker .colorpicker {
	position:relative;
	width:192px;
	height:160px;
	background-color:white;
	border:1px solid silver;
}

.xe_colorpicker .colortable {
	position:absolute;
	left : 4px;
	top  : 4px;
	border:1px solid #909090;
}

.xe_colorpicker .colortable .background {
	position:relative;
	width:150px;
	height:150px;
	background:url(/xe/common/js/plugins/ui.colorpicker/images/colorpicker_overlay.png) no-repeat;
	overflow:hidden;
}

.xe_colorpicker .colortable .indicator {
	position:absolute;
	width:11px;
	height:11px;
	background:url(/xe/common/js/plugins/ui.colorpicker/images/colorpicker_select.gif) no-repeat;
}

.xe_colorpicker .huebar {
	position:absolute;
	right : 4px;
	top   : 4px;
	border:1px solid #909090;
}

.xe_colorpicker .huebar .background {
	width:20px;
	height:150px;
	background:url(/xe/common/js/plugins/ui.colorpicker/images/colorpicker_huebg.png) repeat-x;
}

.xe_colorpicker .huebar .indicator {
	position:absolute;
	width:35px;
	height:9px;
	left:-3px;
	background:transparent url(/xe/common/js/plugins/ui.colorpicker/images/colorpicker_indic.gif) no-repeat;
}

.xe_colorpicker .buttons {
	position:absolute;
	width:194px;
	background-color:black;
}

.xe_colorpicker .buttons button {
	width:33%;
	color:white;
	height:22px;
	border:0;
	background-color:transparent;
	padding:0;
	margin:0;
}
