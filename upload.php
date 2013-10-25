<?
//использовался для заливки данных из csv в базу
mysql_connect("localhost", "root", "") or die(mysql_error());
mysql_select_db("autocomplete") or die(mysql_error());
mysql_query("SET NAMES 'utf8'");

$filer = file("ru-list.csv");

foreach ($filer as $line_num => $line) {
	$info = explode(";", $line);
	mysql_query("INSERT INTO `sity4` (`obl`, `ran`, `punkt`, `koo1`, `koo2`) VALUES ('$info[0]','$info[1]','$info[2]','$info[3]','$info[4]')");
}
?>