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

$upit = pg_query($dbconn2, "SELECT public.\"Blog\".slika_url, public.\"Blog\".naziv, public.\"Korisnik\".ime, public.\"Korisnik\".prezime, public.\"Blog\".datum_kreiranja, public.\"Blog\".sadrzaj, public.\"Blog\".id_bloga FROM public.\"Blog\" JOIN public.\"Korisnik\" ON public.\"Korisnik\".id_korisnika = public.\"Blog\".\"korisnik_FK\" WHERE public.\"Blog\".datum_brisanja IS NULL");

$ispis_blogova = "";

while ($row = pg_fetch_array($upit)) {
    $ispis_blogova .= "<div class=\"col-md-4\">
                                <div class=\"single-post\">
                                    <a href=\"blog_detail.php?id=" . $row[6] . "\"><img src=\"img/" . $row[0] . "\" alt=\"Slika Bloga\" width=320px height=275px></a>
                                    <h3><a href=\"blog_detail.php?id=" . $row[6] . "\">" . $row[1] . "</a></h3>
                                    <h4><span>Posted By: <span class=\"author-name\">" . $row[2] . " " . $row[3] . "</span></span>
                                    </h4>
                                    <p>" . substr($row[5], 0, 200) . "... <a href=\"blog_detail.php?id=" . $row[6] . "\">[Pročitaj više]</a></p>
                                    <h4><span>" . $row[4] . "</span></h4>
                                </div>
                        </div>";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Blogovi</title>
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
    <meta name="naslov" content="Blogovi stranica">
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
                                <li><a href="index.php">Naslovna</a></li>
                                <li class="active"><a href="blog.php">Blogovi</a></li>
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
                            <h3>BLOGOVI</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area" style="margin-bottom: 100px;">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                        <?php echo $ispis_blogova; ?>
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