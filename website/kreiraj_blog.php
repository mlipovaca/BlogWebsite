<?php

$dbconn2 = pg_connect("host=localhost port=5433 dbname=blog_aktivnosti user=postgres password=30781740");

if (!$dbconn2) {
    echo "Couldn't connect to database.";
    exit;
}

session_start();

$ispis_gresaka = "";

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

$upit3 = pg_query($dbconn2, "SELECT public.\"Korisnik\".id_korisnika FROM public.\"Korisnik\" WHERE public.\"Korisnik\".korisnicko_ime = '$korisnik'");

while ($row = pg_fetch_row($upit3)) {
    $idKorisnika = $row[0];
}

$upit1 = pg_query($dbconn2, "SELECT public.\"Vrsta_Bloga\".id_vrste_bloga, public.\"Vrsta_Bloga\".naziv FROM public.\"Vrsta_Bloga\"");

$vrste_blogova = "";

while ($row = pg_fetch_row($upit1)) {
    $vrste_blogova .= '<option value="'.$row[0].'">' . $row[1] . '</option>';
}

$upit2 = pg_query($dbconn2, "SELECT public.\"Blog\".id_bloga, public.\"Blog\".naziv FROM public.\"Blog\" WHERE public.\"Blog\".\"korisnik_FK\" = '$idKorisnika' AND public.\"Blog\".datum_brisanja IS NULL");

$blogovi = "";

while ($row = pg_fetch_row($upit2)) {
    $blogovi .= '<option value="'.$row[0].'">' . $row[1] . '</option>';
}

if (isset($_POST['gumb_kreiraj_blog'])) {
    $nazivBloga = $_POST['naziv_bloga'];
    $vrstaBloga = $_POST['vrsta_bloga'];
    $sadrzajBloga = $_POST['sadrzaj_bloga'];

    if (empty($nazivBloga) || empty($vrstaBloga) || empty($sadrzajBloga)) {
        $ispis_gresaka .= "Morate unijeti sva polja za kreiranje novog bloga!<br><br>";
    }else{
        $slika = $_FILES['userfile']['tmp_name'];
        $nazivSlike = $_FILES['userfile']['name'];
        $errorSlike = $_FILES['userfile']['error'];
        $vrstaSlike = getimagesize($slika);

        if ($errorSlike > 0) {
            $ispis_gresaka .= 'Problem: ';
            switch ($errorSlike) {
                case 1: $ispis_gresaka .= 'Veličina veća od ' . ini_get('upload_max_filesize');
                    break;
                case 2: $ispis_gresaka .= 'Veličina veća od ' . $_POST["MAX_FILE_SIZE"] . 'B';
                    break;
                case 3: $ispis_gresaka .= 'Datoteka djelomično prenesena';
                    break;
                case 4: $ispis_gresaka .= 'Datoteka nije prenesena';
                    break;
            }
        } else if (($vrstaSlike[2] !== IMAGETYPE_JPEG) && ($vrstaSlike[2] !== IMAGETYPE_PNG)) {
            $ispis_gresaka .= "Problem: Vrsta slike nije JPG / PNG!";
        } else {
            $uploadPutanja = 'img/' . $nazivSlike;

            if (is_uploaded_file($slika)) {
                if (!move_uploaded_file($slika, $uploadPutanja)) {
                    $ispis_gresaka .= "Problem: nije moguće prenijeti datoteku na odredište";
                } else {
                    pg_query($dbconn2, "INSERT INTO public.\"Blog\" VALUES (DEFAULT, '$nazivBloga', NOW(), 0, '$idKorisnika', '$vrstaBloga', NULL, '$nazivSlike', '$sadrzajBloga')");
                }
            }
        }
    }
}

if (isset($_POST['gumb_izbrisi_blog'])) {
    $idBloga = $_POST['ime_bloga'];
    pg_query($dbconn2, "UPDATE public.\"Blog\" SET datum_brisanja = NOW() WHERE public.\"Blog\".id_bloga = '$idBloga'");
    pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (DEFAULT, NOW(), 'Korisnik je izbrisao blog')");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teorija Baza Podataka - Kreiraj/Izbrisi blogove</title>
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
    <meta name="naslov" content="Kreiraj/Izbrisi blogove stranica">
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
                            <h3>KREIRAJ / IZBRISI BLOGOVE</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area" style="margin-bottom: 100px;">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                        <h1 style="text-align: center; color:#4069ff; font-size: 55px; margin-bottom: 50px">KREIRAJ BLOG</h1>
                        <?php
                            echo '<p style="text-align: center;">' . $ispis_gresaka . '</p>';
                        ?>
                        <form novalidate id="kreirajBlog" enctype="multipart/form-data" method="post" name="login" action="kreiraj_blog.php" style="margin:0 auto; width:350px;">
                            <p><label class="label_registracija" for="naziv_bloga">Naziv Bloga: </label>
                                <input type="text" id="naziv_bloga" name="naziv_bloga" maxlength="20" required="required"><br><br>
                                <label class="label_registracija" for="vrsta_bloga">Vrsta Bloga: </label>
                                <select id="vrsta_bloga" style="margin-left: 1%" name="vrsta_bloga" required="required">
                                    <?php echo $vrste_blogova; ?>
                                </select><br><br>
                                <label class="label_registracija" for="sadrzaj_bloga">Sadržaj Bloga: </label>
                                <textarea rows="4" cols="50" name="sadrzaj_bloga"></textarea><br><br>
                                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                                <label>Upload sliku za blog: </label>
                                <input name="userfile" type="file"><br><br>
                                <input type="submit" class="button" name="gumb_kreiraj_blog" value=" Kreiraj Blog "><br><br>
                        </form>
                        <hr>
                        <h1 style="text-align: center; color:#4069ff; font-size: 55px; margin-bottom: 50px">IZBRIŠI BLOG</h1>
                        <form novalidate id="kreirajBlog" enctype="multipart/form-data" method="post" name="login" action="kreiraj_blog.php" style="margin:0 auto; width:350px;">
                            <p><label class="label_registracija" for="ime_bloga">Ime Bloga: </label>
                                <select id="ime_bloga" style="margin-left: 1%" name="ime_bloga" required="required">
                                    <?php echo $blogovi; ?>
                                </select><br><br>
                                <input type="submit" class="button" name="gumb_izbrisi_blog" value=" Izbriši Blog "><br><br>
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