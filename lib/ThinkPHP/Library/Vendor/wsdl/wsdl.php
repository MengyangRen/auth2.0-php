<?php
include_once("wsdl/lib/soap/IPReflectionClass.class.php");
include_once("wsdl/lib/soap/IPReflectionCommentParser.class.php");
include_once("wsdl/lib/soap/IPReflectionMethod.class.php");
include_once("wsdl/lib/soap/IPReflectionProperty.class.php");
include_once("wsdl/lib/soap/IPXMLSchema.class.php");
include_once("wsdl/lib/soap/WSDLStruct.class.php");
include_once("wsdl/lib/soap/WSHelper.class.php");
include_once("wsdl/lib/soap/WSException.class.php");
include_once("wsdl/lib/soap/WSDLException.class.php");
include_once("wsdl/lib/IPXSLTemplate.class.php");

function dbg($txt,$file="/home/chrishuang/public_html/feedcenter/app/tmp/cache/persistent/debug.txt"){
	$fp = fopen($file, "a");
	fwrite($fp, str_replace("\n","\r\n","\r\n".$txt));
	fclose($fp);
}
?>