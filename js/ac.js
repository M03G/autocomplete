var timeout;	
$(document).ready(function(){
	//при нажатии Enter, в поле подставляется значение из первой строки
	$("#qcity").bind("keypress", function(l) {
		if (l.keyCode == 13) {
			$("#qcity").val($("#city_result a").first().attr("town"));
		}
	});
});
function queryTown() {
	//острочка на 0,25 сек
	clearTimeout(timeout);
	timeout=setTimeout("checkTown()",250);
}
function checkTown(type) {
	var url='find_city.php?ci='+encodeURIComponent($("#qcity").val());
	if($("#qcity").val() == ""){
		$("#city_result").css("display","none");			
	} else {
		$("#city_result").css("display","inline");
	}
	$("#city_result").load(url);			
}	 	 
 function addTown(id) {
	$("#qcity").val($("#town_"+id).attr("town"));
	$("#town_"+id).css("color","#0059bc");
}