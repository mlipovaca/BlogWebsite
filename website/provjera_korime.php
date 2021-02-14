<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

if (!$dbconn2) {
    echo "Couldn't connect to database.";
    exit;
}

if (isset($_POST['korime'])) {
    $korIme = $_POST['korime'];
    $upitKorime = pg_query($dbconn2, "SELECT public.\"Korisnik\".korisnicko_ime FROM public.\"Korisnik\" WHERE public.\"Korisnik\".korisnicko_ime = '$korIme'");
    
    if (pg_num_rows($upitKorime) > 0) {
        echo "Postoji_korime";
    }
    else{
        echo "Nepostoji_korime";
    }
    exit();
}

pg_close();