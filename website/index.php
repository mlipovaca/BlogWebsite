<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

if (!$dbconn2) {
    echo "Couldn't connect to database.";
    exit;
}

session_start();

if (!empty($_SESSION['korisnik'])) {
    $korisnik = $_SESSION['korisnik'];
}

$upit1 = pg_query($dbconn2, "SELECT public.\"Blog\".slika_url, public.\"Blog\".naziv, public.\"Korisnik\".ime, public.\"Korisnik\".prezime, public.\"Blog\".datum_kreiranja, public.\"Blog\".sadrzaj FROM public.\"Blog\" JOIN public.\"Korisnik\" ON public.\"Korisnik\".id_korisnika = public.\"Blog\".\"korisnik_FK\" WHERE public.\"Blog\".id_bloga = 6 AND public.\"Blog\".datum_brisanja IS NULL");

while ($row = pg_fetch_row($upit1)) {
    $slikaDohvat = $row[0];
    $nazivDohvat = $row[1];
    $imeDohvat = $row[2];
    $prezimeDohvat = $row[3];
    $datumDohvat = $row[4];
    $sadrzajDohvat = $row[5];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Naslovna</title>
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
    <meta name="naslov" content="Početna stranica">
    <meta name="autor" content="Matej Lipovača">
</head>

<body>
    <div class="wrapper">
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-1">
                        <div class="logo">
                            <h2><a href="index.php">BLOGOVI</a></h2>
                        </div>
                    </div>
                    <div class="col-md-14">
                        <div class="menu">
                            <ul>
                                <li class="active"><a href="index.php">Naslovna</a></li>
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
                            <h3>NASLOVNA</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area" style="margin-bottom: 100px">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                            <div class="col-md-12">
                                <div class="single-post-big">
                                    <h1 style="text-align: center; color:#4069ff; font-size: 85px; margin-bottom: 100px">BLOG TJEDNA</h1>
                                    <div class="big-image">
                                        <img src="img\<?php echo $slikaDohvat;?>" alt="">
                                    </div>
                                    <div class="big-text">
                                        <h3><?php echo $nazivDohvat; ?></h3>
                                        <p><?php echo substr($sadrzajDohvat, 0, 200) . "..."; ?><a href="blog_detail.php?id=1">[Pročitaj više]</a></p>
                                        <h4><span class="date"><?php echo $datumDohvat; ?></span><span class="author">Autor: <span class="author-name"><?php echo $imeDohvat . " " . $prezimeDohvat; ?></span></span>
                                        </h4>
                                    </div>
                                </div>
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