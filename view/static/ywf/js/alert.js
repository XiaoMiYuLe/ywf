function show(tag){
 var light=document.getElementById(tag); //var light = $("#light");
 var fade=document.getElementById('fade'); //var fade = $("#fade");
 light.style.display='block';             
 fade.style.display='block';
 }
function hide(tag){
 var light=document.getElementById(tag);
 var fade=document.getElementById('fade');
 light.style.display='none';
 fade.style.display='none';
}