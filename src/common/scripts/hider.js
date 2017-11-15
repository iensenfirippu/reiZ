var lastid = "";

function showdiv(divid)
{
	if (window.lastid != "")
	{
		hidediv(window.lastid);
	}
	document.getElementById(divid).className = "unhidden";
	window.lastid = divid;
}

function hidediv(divid)
{
   document.getElementById(divid).className = "hidden";
}
