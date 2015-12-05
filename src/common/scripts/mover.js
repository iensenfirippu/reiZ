function movediv(divid, x, y)
{
	var style = document.getElementById(divid).style;
	var startx = style.left;
	var starty = style.top;

	var speedx = 1;
	var speedy = 1;
	var maxspeed = 5;
	
	while (x != 0 || y != 0)
	{
		if (x > 0) { x -= speedx; }
		else if (x < 0) { x += speedx; }
		if (y > 0) { y -= speedy; }
		else if (y < 0) { y += speedy; }
		
		style.left = x;
		style.top = y;

		if (x < 10 && speedx > 1) { speedx--; }
		else if (x > 10 && speedx < maxspeed) { speedx++; }
		if (y < 10 && speedy > 1) { speedy--; }
		else if (y > 10 && speedy < maxspeed) { speedy++; }
	}
}
