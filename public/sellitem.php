<?php

include 'header.php';

if (!isset($_GET['id']) || isset($_GET['id']) && $_GET['id'] == "") {
	echo Message("No item picked.");
	include 'footer.php';
	die();
}	

$howmany = Check_Item($_GET['id'], $user_class->id);//check how many they have
$result2 = DB::run("SELECT * FROM `items` WHERE `id`='".$_GET['id']."'");
$worked = $result2->fetch();

$price = $worked['cost'] * .60;

// if they confirm they want to sell it
if (isset($_GET['confirm']) && $_GET['confirm'] == "true") {
	$error = ($howmany == 0) ? "You don't have any of those." : null;
	if (isset($error)) {
		echo Message($error);
		include 'footer.php';
		die();
	}

	$newmoney = $user_class->money + $price;
	$result = DB::run("UPDATE `grpgusers` SET `money` = '".$newmoney."' WHERE `id`='".$_SESSION['id']."'", [$newmoney, $_SESSION['id']]);

	Take_Item($_GET['id'], $user_class->id);
	echo Message("You have sold a ".$worked['itemname']." for $".$price.".");
	include 'footer.php';
	die();
}
?>
<tr><td class="contenthead">Sell Item</td></tr>
<tr><td class="contentcontent">
<?= "Are you sure that you want to sell ".$worked['itemname']." for $".$price."?<br><a href='sellitem.php?id=".$_GET['id']."&confirm=true'>Yes</a>"; ?>
</td></tr>
<?php
include 'footer.php';
?>