<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

session_start();

if (!$dbconn2) {
	echo "Couldn't connect to database.";
	exit;
}

$korisnik = $_SESSION['korisnik'];

$poljeLajkova = array();
$upitLajkova = pg_query($dbconn2, "SELECT public.\"Blog\".naziv, COUNT(public.\"Lajkovi\".\"blog_FK\") FROM public.\"Korisnik\" JOIN public.\"Blog\" ON public.\"Korisnik\".id_korisnika = public.\"Blog\".\"korisnik_FK\" JOIN public.\"Lajkovi\" ON public.\"Lajkovi\".\"blog_FK\" = public.\"Blog\".id_bloga AND public.\"Korisnik\".korisnicko_ime = '$korisnik' AND public.\"Blog\".datum_brisanja IS NULL GROUP BY public.\"Blog\".naziv");

while ($podaciLajkova = pg_fetch_array($upitLajkova)) {
    $rezultatLajkova = array("korisnici" => $podaciLajkova['naziv'], "brojLajkova" => $podaciLajkova[1]);

    array_push($poljeLajkova, $rezultatLajkova );
}

echo json_encode($poljeLajkova, JSON_NUMERIC_CHECK);

pg_close();