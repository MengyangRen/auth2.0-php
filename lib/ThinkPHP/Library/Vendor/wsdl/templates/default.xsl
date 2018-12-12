<xsl:stylesheet 
	version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl"
	xmlns:ipub="http://www.ipublisher.nl/4.0"
	xmlns:exsl="http://exslt.org/common"
	xmlns:str="http://exslt.org/strings"
	xmlns:date="http://exslt.org/dates-and-times"
	extension-element-prefixes="str exsl date"
	>
	<xsl:include href="str.replace.function.xsl"/>	
	<xsl:output method="xml" encoding="utf-8" indent="yes" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" media-type="text/html"/>

	<xsl:template match="/doc">
		<html>
			<head>
				<title>Webservice Helper</title>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
				<style type="text/css">
					<![CDATA[
					#index {float:left;}
					#class {float:left;margin-left: 120px;}
					.warning {color: red}
					.comment {white-space: pre-line;margin: 0;font-size: 10px;color: #666;}
					.property, .method {margin: 0 0 25px 5px;}
					.method {border: 1px solid #B3ADD0; padding: 20px;}
					]]>
				</style>
				<script>
					$(document).ready(function(){
					$('.rest_url').click(function(){
					var newtab = window.open();
					rest_url = $(this).html();
					rest_url = rest_url.replace(/&amp;/gm, '%26');
					newtab.location = decodeURIComponent(rest_url);
					});
					});
				</script>
			</head>
			<body>
				<div id="header">
					<h1>Api Doc</h1>
					<xsl:if test="fault != ''">
						<xsl:value-of select="fault" />
					</xsl:if>
				</div>

				<div id="index">
					<h2>Index</h2>
					<ul>
						<!-- <xsl:for-each select="/doc/classes/*">
							<li><a href="?class={name}"><xsl:value-of select="name"/></a><br /></li>
						</xsl:for-each> -->
						<li><xsl:value-of select="class/name" /></li>
					</ul>
				</div>

				<xsl:if test="class != ''">
					<div id="class">

						<h2><xsl:value-of select="class/name" /> <!--  <a href="soap?class={class/name}&amp;wsdl">&#160;[WSDL]</a>&#160;<a href="rest/{class/name}/*">&#160;[REST]</a> --></h2>
						<p>(总体说明: <xsl:value-of select="class/fullDescription" />)</p>

						<h3>Methods 方法列表</h3>
						<xsl:for-each select="class/methods/*">
							<!-- <a name="method_{name}">#</a> -->
							<xsl:if test="name != '__construct'">
								<div class="method{warning}">
									<pre class="comment"><xsl:value-of select="comment" /></pre>
									<br/>
									<pre class="comment">#访问接口： 
										<span class="rest_url">./rest/<xsl:value-of select="classname" />/<xsl:value-of select="name" />?<xsl:for-each select="params/*"><xsl:value-of select="name"/>=<xsl:if test="position() != last()">&amp;</xsl:if></xsl:for-each>
										</span>
									</pre>
									<br/>
									<b>function <xsl:value-of select="name" /></b>(
									<xsl:for-each select="params/*">
										<xsl:value-of select="name"/>
										<xsl:if test="position() != last()">, </xsl:if>
									</xsl:for-each>
									)
									<!--
									<xsl:choose>
										<xsl:when test="throws != ''">
											<i>throws  <xsl:value-of select="throws" /></i><br />
										</xsl:when>
									</xsl:choose>
									-->
								</div>
							</xsl:if>
						</xsl:for-each>
					</div>
				</xsl:if>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>