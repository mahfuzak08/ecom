function printDiv(id) 
{

  var divToPrint=document.getElementById(id);

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  //setTimeout(function(){newWin.close();},10);

}