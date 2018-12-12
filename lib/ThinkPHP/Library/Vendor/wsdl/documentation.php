<?php
//need to manually include for the function 'get_declared_classes()'
include_once("wsdl/lib/soap/IPPhpDoc.class.php");
include_once("wsdl/lib/soap/IPReflectionClass.class.php");
include_once("wsdl/lib/soap/IPReflectionCommentParser.class.php");
include_once("wsdl/lib/soap/IPReflectionMethod.class.php");
include_once("wsdl/lib/soap/IPReflectionProperty.class.php");
include_once("wsdl/lib/soap/IPXMLSchema.class.php");
include_once("wsdl/lib/soap/WSDLStruct.class.php");
include_once("wsdl/lib/soap/WSHelper.class.php");
include_once("wsdl/lib/IPXSLTemplate.class.php");
include_once("wsdl/lib/soap/WSException.class.php");
include_once("wsdl/lib/soap/WSDLException.class.php");

/*
$phpdoc=new IPPhpdoc();
if(isset($_GET['class'])) $phpdoc->setClass($_GET['class']);
echo $phpdoc->getDocumentation();
?>
*/