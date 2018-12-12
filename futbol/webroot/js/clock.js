/* http://www.peters1.dk/webtools/javascript/ur.php?sprog=en */
function clock_start() 
{
	UR_Nu = new Date;
	UR_Indhold = clock_showFilled(UR_Nu.getHours()) + ":" + clock_showFilled(UR_Nu.getMinutes()) + ":" + clock_showFilled(UR_Nu.getSeconds());
	document.getElementById("clock").innerHTML = UR_Indhold;
	setTimeout("clock_start()",1000);
}
function clock_showFilled(Value) 
{
	return (Value > 9) ? "" + Value : "0" + Value;
}