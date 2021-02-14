<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

if (!$dbconn2) {
	echo "Couldn't connect to database.";
	exit;
}

session_start();

$korisnik = $_SESSION['korisnik'];

if (isset($_SESSION['korisnik'])) {
    pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (default, NOW(), 'Odjava iz sustava')");
    session_destroy();
    session_unset();
}

pg_close($dbconn2);
header("Location: login.php");
exit();