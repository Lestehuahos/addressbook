<?php
session_start();
ob_start();


/* ���������� � ����� ������ */
$user_PDO = "mysql";
$pass_PDO = "mysql";
$pdo = new PDO("mysql:host=localhost;dbname=address_book", $user_PDO, $pass_PDO);


/* ����������� ����� */
function text($m)
{   
	$m = trim($m);
	$m = htmlspecialchars($m);
	return $m;
}
	
?>