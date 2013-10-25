<?php
header('Content-type: text/html; charset=utf-8');
mysql_connect("localhost", "root", "") or die(mysql_error());
mysql_select_db("autocomplete") or die(mysql_error());
mysql_query("SET NAMES 'utf8'");

//поиск и выделение искомой части в найденой строке
function str_light($search, $str){
	$posFragment = strpos(mb_strtolower($str, 'UTF-8'), mb_strtolower($search, 'UTF-8'));
	$fragment = mb_substr($str, $posFragment, strlen($search));
	return str_replace($fragment,'<strong>'.$fragment.'</strong>', $str);	
}

function textswitch($text) {
   $str_search = array(
   "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
   "ф","ы","в","а","п","р","о","л","д","ж","э",
   "я","ч","с","м","и","т","ь","б","ю"
   );
   $str_replace = array(
   "q","w","e","r","t","y","u","i","o","p","[","]",
   "a","s","d","f","g","h","j","k","l",";","'",
   "z","x","c","v","b","n","m",",","."
   );
   return str_replace($str_replace, $str_search, $text);
}

//проверяем, надо ли добавить слово "область" в выводе (например, в "республика Дагестан" не надо)
function checkObl($oblast) {
	if (substr_count($oblast, " ") > 0){
			$obl = "";
		} else {
			$obl = "область";
		}
	return $obl;
}

if ($_GET[ci]) {
	$citys = mysql_query("SELECT * FROM `sity4` WHERE UPPER(`punkt`) LIKE UPPER('$_GET[ci]%') OR UPPER(`punkt`) LIKE UPPER('".textswitch($_GET[ci])."%') ORDER BY `punkt` LIMIT 10");
	// $users = mysql_query("SELECT * FROM `sity3` WHERE MATCH (town) AGAINST ('$_GET[ci]*' IN BOOLEAN MODE)");		
	$countRows = mysql_num_rows($citys);
	while ($city = mysql_fetch_array($citys)) {		
		echo "<a href='javascript:void(0)' onclick='addTown($city[id])' town='$city[punkt]' id='town_$city[id]'>".str_light(textswitch($_GET[ci]), $city[punkt]).", $city[obl] ".checkObl($city[obl])." ($city[ran] район)</a>";
		echo "<br>";
	}
	//если найдено меньше 10 городов, которые начинаются с введённой комбинации, то ищем вторым запросом в середине слова
	if ($countRows < 10) {
		$diff = 10 - $countRows;
		$citys = mysql_query("SELECT * FROM `sity4` WHERE (UPPER(`punkt`) NOT REGEXP UPPER('^".textswitch($_GET[ci])."')) AND (UPPER(`punkt`) LIKE UPPER('%$_GET[ci]%') OR UPPER(`punkt`) LIKE UPPER('%".textswitch($_GET[ci])."%')) ORDER BY `punkt` LIMIT $diff");
		while ($city = mysql_fetch_array($citys)) {			
			echo "<a href='javascript:void(0)' onclick='addTown($city[id])' town='$city[punkt]' id='town_$city[id]'>".str_light(textswitch($_GET[ci]), $city[punkt]).", $city[obl] ".checkObl($city[obl])." ($city[ran] район)</a>";
			echo "<br>";
		}
	}
}
?>