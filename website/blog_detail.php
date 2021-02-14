<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

if (!$dbconn2) {
    echo "Couldn't connect to database.";
    exit;
}

session_start();

$ispis_gresaka = "";

$id_bloga = $_GET['id'];

$korisnik = $_SESSION['korisnik'];

$brojac_prvi_put = 0;

if (!isset($_SESSION['korisnik'])){
    echo "Morate big registriran korisnik da vidite svoju statistiku oglasa!";
    header("Location: index.php");
    die();
}

$upit = pg_query($dbconn2, "SELECT public.\"Korisnik\".\"uloga_FK\" FROM public.\"Korisnik\" WHERE public.\"Korisnik\".korisnicko_ime = '$korisnik'");

while ($row = pg_fetch_row($upit)) {
    if ($row[0] == 3 || !(isset($korisnik))){
        echo "Morate big registriran korisnik da vidite svoju statistiku oglasa!";
        header("Location: index.php");
        exit;
    }
}

$upit3 = pg_query($dbconn2, "SELECT public.\"Korisnik\".id_korisnika FROM public.\"Korisnik\" WHERE public.\"Korisnik\".korisnicko_ime = '$korisnik'");

while ($row = pg_fetch_row($upit3)) {
    $idKorisnika = $row[0];
}

$upit1 = pg_query($dbconn2, "SELECT public.\"Blog\".slika_url, public.\"Blog\".naziv, public.\"Korisnik\".ime, public.\"Korisnik\".prezime, public.\"Blog\".datum_kreiranja, public.\"Blog\".sadrzaj FROM public.\"Blog\" JOIN public.\"Korisnik\" ON public.\"Korisnik\".id_korisnika = public.\"Blog\".\"korisnik_FK\" WHERE public.\"Blog\".id_bloga = '$id_bloga'");

while ($row = pg_fetch_row($upit1)) {
    $slikaDohvat = $row[0];
    $nazivDohvat = $row[1];
    $imeDohvat = $row[2];
    $prezimeDohvat = $row[3];
    $datumDohvat = $row[4];
    $sadrzajDohvat = $row[5];
}

pg_query("UPDATE public.\"Blog\" SET broj_klikova = broj_klikova + 1 WHERE public.\"Blog\".id_bloga = '$id_bloga'");

$upit2 = pg_query($dbconn2, "SELECT COUNT(public.\"Lajkovi\".\"blog_FK\") FROM public.\"Lajkovi\" WHERE public.\"Lajkovi\".\"blog_FK\" = '$id_bloga'");

while ($row = pg_fetch_row($upit2)) {
    $broj_lajkova = $row[0];
}

$upit4 = pg_query($dbconn2, "SELECT public.\"Lajkovi\".\"korisnik_FK\" FROM public.\"Lajkovi\" WHERE public.\"Lajkovi\".\"korisnik_FK\" = '$idKorisnika' AND public.\"Lajkovi\".\"blog_FK\" = '$id_bloga'");


$provjeraLajka = "";

if (pg_num_rows($upit4) > 0) {
    $provjeraLajka .= '<button style="border:0px; background:transparent;" type="submit" name="button_odlajk"><i style="color: blue;" class="fa fa-thumbs-up" aria-hidden="true"></i></button>';
}
else {
    $provjeraLajka .= '<button style="border:0px; background:transparent;" type="submit" name="button_lajk"><i class="fa fa-thumbs-up" aria-hidden="true"></i></button>';
}

if (isset($_POST['button_lajk'])) {
    pg_query($dbconn2, "INSERT INTO public.\"Lajkovi\" VALUES (DEFAULT, '$id_bloga', '$idKorisnika')");
    pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (default, NOW(), 'Korisnik je lajkao blog')");
    header("Location: blog_detail.php?id=$id_bloga&like=success");
}

if(isset($_POST['button_odlajk'])) {
    pg_query($dbconn2, "DELETE FROM public.\"Lajkovi\" WHERE public.\"Lajkovi\".\"blog_FK\" = '$id_bloga' AND public.\"Lajkovi\".\"korisnik_FK\" = '$idKorisnika'");
    pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (default, NOW(), 'Korisnik je odlajkao blog')");
    header("Location: blog_detail.php?id=$id_bloga&like=success");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Blog</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" rel="stylesheet">
    <link rel="icon" href="img/icon.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta name="naslov" content="Blog stranica">
    <meta name="autor" content="Matej Lipovača">
</head>

<body>
    <div class="wrapper">
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="logo">
                            <h2><a href="index.php">BLOGOVI</a></h2>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="menu">
                            <ul>
                                <li><a href="index.php">Naslovna</a></li>
                                <li><a href="blog.php">Blogovi</a></li>
                                <?php if (!(empty($_SESSION['korisnik']))){
                                    echo '<li><a href="kreiraj_blog.php">Kreiraj/Izbrisi Blog</a></li>';
                                }
                                ?>
                                <li><a href="statistika.php">Statistika</a></li>
                                <?php if ((empty($_SESSION['korisnik']))){
                                    echo '<li><a href="registracija.php">Registracija</a></li>';
                                }
                                ?>
                                <?php if ((empty($_SESSION['korisnik']))){
                                    echo '<li><a href="login.php">Login</a></li>';
                                }
                                else{
                                    echo '<li><a href="logout.php">Logout</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="bg-text-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="bg-text">
                            <h3><?php echo $nazivDohvat; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area" style="margin-bottom: 100px;">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                        <div class="big-image">
                            <img src="img\<?php echo $slikaDohvat;?>" alt="">
                                </div>
                                    <div class="big-text">
                                        <h3><?php echo $nazivDohvat; ?></h3>
                                        <p><?php echo $sadrzajDohvat; ?></p>
                                        <h4><span class="date"><?php echo $datumDohvat; ?></span><span class="author">Autor: <span class="author-name"><?php echo $imeDohvat . " " . $prezimeDohvat; ?></span></span>
                                        </h4>
                                        <h4><form novalidate id="provjera_lajka" method="post" name="provjera_lajka" action="blog_detail.php?id=<?php echo $id_bloga; ?>">
                                            <?php echo $provjeraLajka; ?><span style="margin-left: 2%"><?php echo $broj_lajkova; ?></span></form></h4>
                                    </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer-bg">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="footer-icon">
                                        <p><a href="https://wwww.facebook.com/matej.lipovaca"><i class="fa fa-facebook" aria-hidden="true"></i></a><a href="https://twitter.com/rix_hd"><i class="fa fa-twitter" aria-hidden="true"></i></a><a href="https://hr.linkedin.com/in/matej-lipova%C4%8Da-234927154"><i class="fa fa-linkedin" aria-hidden="true"></i></a></p>
                                    </div>
                                </div>
                                <p style="color: #fff; margin:50px; text-align: right; font-style: italic;">Copyrights © Matej Lipovača, 2020.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/active.js"></script>
</body>

</html>