<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

session_start();

if (!$dbconn2) {
	echo "Couldn't connect to database.";
	exit;
}

$korisnik = $_SESSION['korisnik'];

$poljeKlikova = array();
$upitKlikova = pg_query($dbconn2, "SELECT public.\"Blog\".naziv, public.\"Blog\".broj_klikova FROM public.\"Korisnik\" JOIN public.\"Blog\" ON public.\"Korisnik\".id_korisnika = public.\"Blog\".\"korisnik_FK\" WHERE public.\"Blog\".datum_brisanja IS NULL AND public.\"Korisnik\".korisnicko_ime = '$korisnik'");

while ($podaciKlikova = pg_fetch_array($upitKlikova)) {
    $rezultatKlikova = array("korisnici" => $podaciKlikova['naziv'], "brojKlikova" => $podaciKlikova['broj_klikova']);

    array_push($poljeKlikova, $rezultatKlikova );
}

echo json_encode($poljeKlikova, JSON_NUMERIC_CHECK);

pg_close();