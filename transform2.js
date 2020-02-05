var xml = null;
var xsl = null;

function getPhotos() {
    var tags = $("#tags").val();
    var user = $("#user").val();
    var pwd = $("#pwd").val();
    var url = "flickr.pl?tags=" + tags+"&user="+user+"&pwd="+pwd;
    transform(url, "flickr.xsl");
}

function transform (xmlFileName, xslFileName) {
  xml=null; xsl=null;

  var settings = {
    type: "GET",
    error: function(xhr, status, error) { 
                 window.alert("Failed to load the input as an XML document!");
		 window.alert("Raw text was: " + xhr.responseText);
    }};

  // Get the XML input data
  settings.url = xmlFileName;
  settings.success = function(data) { xml = data; insertXML(); };
  $.ajax(settings);

  // Get the XSLT file
  settings.url = xslFileName;
  settings.success = function(data) { xsl = data; insertXML(); };
  $.ajax(settings);
}

function insertXML() {
  // Make sure both AJAX requests have returned.
  if( xml != null && xsl != null ) {
    
    // The 'xml' variable holds something, but is it a valid XML document?
    if (jQuery.isXMLDoc(xml) == false) {
        window.alert("XML data file is not proper XML!");
        window.alert("Raw text was:\n "+xml);
        return;
    }

    // Transform the XML via the XSLT
    var processor = new XSLTProcessor();
    processor.importStylesheet(xsl);
    var newDocument = processor.transformToDocument(xml);
    if (newDocument == null) {
	window.alert("Failed to convert the new document!");
	return;
    }

    // Replace part of original document with the new content
    var o = document.getElementById("planet");
    if (o == null) {
	window.alert("Failed to find element for 'o'!");
	return;
    }

    var n = newDocument.getElementById("planet");
    if (n == null) {
	window.alert("Failed to find element for 'n'!");
	return;
    }
	
    o.parentNode.replaceChild(n, o);

    // Reset the variables to null.
    xml = null;
    xsl = null;
  }
}