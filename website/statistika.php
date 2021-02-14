<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

session_start();

$korisnik = $_SESSION['korisnik'];

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Statistika</title>
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
    <meta name="naslov" content="Statistika stranica">
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
                                <li><a href="blog.php">Blogovi</a></li>
                                <?php if (!(empty($_SESSION['korisnik']))){
                                    echo '<li><a href="kreiraj_blog.php">Kreiraj/Izbrisi Blog</a></li>';
                                }
                                ?>
                                <li class="active"><a href="statistika.php">Statistika</a></li>
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
                            <h3>STATISTIKA</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                    <div id="grafKlikovaKorisnici" style="margin-left:auto; margin-right: auto; height: 60%; width: 50%;"></div>
                    <div style="height:500px"></div>
                    <div id="grafLajkovaKorisnici" style="margin-left:auto; margin-right: auto; height: 60%; width: 50%;"></div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer" style="margin-top: 30%;">
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
    <script src="js/statistika.js" type="text/javascript"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
</body>

</html>