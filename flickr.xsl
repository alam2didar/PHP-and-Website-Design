<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" >
    <xsl:template match="/">
        <html>
          <head>
                <title> Flickr test </title>
          </head>
          <body> <div id="planet">
	        <!-- Define variable for total number of matches. @total gets the 'total' attribute of <photos> -->
	        <xsl:variable name="var_total" select="/rsp/photos/@total" />

		    <p>There were <xsl:value-of select="$var_total" /> results.  Here are just some: </p>
		    <ul> <xsl:apply-templates select="/rsp/photos/photo" /> </ul>
          </div> </body>
        </html>
    </xsl:template>
    <xsl:template match="photo">  <!-- Handle one photo -->          
	    <!-- create a variable for the image url 
	         A whole URL for this is like: http://farm1.staticflickr.com/2/1418878_1e92283336.jpg -->
	    <xsl:variable name="url">http://farm<xsl:value-of select="@farm" />.staticflickr.com/<xsl:value-of select="@server" />/<xsl:value-of select="@id" />_<xsl:value-of select="@secret" />.jpg</xsl:variable>

	    <!-- show the actual image, with a link to it --> 
	    <p>	<li> <a href="{$url}"> <img src="{$url}" /> </a> </li> </p>
    </xsl:template>
</xsl:stylesheet>
