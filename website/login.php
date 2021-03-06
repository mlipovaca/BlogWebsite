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

$ispis_greske = "";

if (isset($_POST['gumb_prijava'])) {
    $korime = $_POST['korime_prijava'];
    $lozinka = $_POST['lozinka_prijava'];

    $upit = pg_query($dbconn2, "SELECT lozinka FROM public.\"Korisnik\" WHERE korisnicko_ime = '$korime'");

    while ($row = pg_fetch_row($upit)) {
        $lozinkaDohvat = $row[0];
    }

    if ($lozinkaDohvat == $lozinka){
        $_SESSION['korisnik'] = $korime;
        pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (default, NOW(), 'Prijava u sustav')");
        header("Location: index.php");
        exit;
    }
    else{
        $ispis_greske .= "Upisali ste krivu lozinku !";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Login</title>
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
    <meta name="naslov" content="Login stranica">
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
                                    echo '<li class="active"><a href="kreiraj_blog.php">Kreiraj/Izbrisi Blog</a></li>';
                                }
                                ?>
                                <li><a href="statistika.php">Statistika</a></li>
                                <?php if ((empty($_SESSION['korisnik']))){
                                    echo '<li><a href="registracija.php">Registracija</a></li>';
                                }
                                ?>
                                <?php if ((empty($_SESSION['korisnik']))){
                                    echo '<li class="active"><a href="login.php">Login</a></li>';
                                }
                                else{
                                    echo '<li class="active"><a href="logout.php">Logout</a></li>';
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
                            <h3>LOGIN</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                        <form novalidate id="login" method="post" name="login" action="login.php" style="margin:0 auto; width:350px;">
                            <p><label class="label_registracija" for="korime_prijava">Korisničko ime: </label>
                                <input type="text" id="korime_prijava" name="korime_prijava" maxlength="20" placeholder="korisničko ime" required="required"><br><br>
                                <label class="label_registracija" for="lozinka_prijava">Lozinka: </label>
                                <input type="password" id="lozinka_prijava" style="margin-left: 14%" name="lozinka_prijava" placeholder="lozinka" required="required"><br><br>
                                <input type="submit" class="button" name="gumb_prijava" value=" Login ">
                                <input type="reset" class="button" style="margin-left: 23%" value=" Inicijaliziraj "><br><br>
                                <?php echo $ispis_greske; ?>
                        </form>
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