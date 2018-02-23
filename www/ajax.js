function findDupes(d,s)
{				
	var xmlHttp;
	
	// Firefox, Opera 8.0+, Safari
	try
	{
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			try
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e)
			{
				return false;
			}
		}
	}
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			document.getElementById('showDupe').innerHTML=xmlHttp.responseText;
		}
	}

	xmlHttp.open("GET","dupe.php?ajax=1&dob="+d+"&season="+s,true);
	xmlHttp.send(null);
}