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

if (isset($_POST['gumb_registracija'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $korime = $_POST['korime'];
    $lozinka = $_POST['lozinka'];
    $email = $_POST['email'];
    $spol = $_POST['spol'];

    if (empty($ime) || empty($prezime) || empty($korime) || empty($lozinka) || empty($email) || empty($spol)) {
        $ispis_greske .= "Morate unijeti sva polja za registraciju!<br><br>";
    }
    else {
        pg_query($dbconn2, "INSERT INTO public.\"Korisnik\" VALUES (default, '$ime', '$prezime', '$korime', '$lozinka', '$email', 2, '$spol')");
        pg_query($dbconn2, "INSERT INTO public.\"Dnevnik_Aktivnosti\" VALUES (DEFAULT, NOW(), 'Korisnik se registrirao')");
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
    <title>Teorija Baza Podataka - Registracija</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900" rel="stylesheet">
    <link rel="icon" href="img/icon.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <meta name="naslov" content="Registracija stranica">
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
                                <li><a href="statistika.php">Statistika</a></li>
                                <?php if ((empty($_SESSION['korisnik']))){
                                    echo '<li class="active"><a href="registracija.php">Registracija</a></li>';
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
                            <h3>REGISTRACIJA</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="blog-post-area">
            <div class="container">
                <div class="row">
                    <div class="blog-post-area-style">
                        <form novalidate id="registracija" method="post" name="registracija" action="registracija.php" style="margin:0 auto; width:350px;">
                            <p><label class="label_registracija" for="ime">Ime: </label>
                                <input type="text" id="ime" style="margin-left: 20%" name="ime" maxlength="20" required="required"><br><br>
                                <label class="label_registracija" for="prezime">Prezime: </label>
                                <input type="text" id="prezime" style="margin-left: 12%" name="prezime" required="required"><br><br>
                                <label class="label_registracija" for="korime">Korisničko ime: </label>
                                <input type="text" id="korime" name="korime" required="required"><br><br>
                                <label class="label_registracija" for="lozinka">Lozinka: </label>
                                <input type="password" id="lozinka" style="margin-left: 14%" name="lozinka" required="required"><br><br>
                                <label class="label_registracija" for="potvrdaLozinke">Potvrda lozinke: </label>
                                <input type="password" id="potvrdaLozinke" name="potvrdaLozinke" required="required"><br><br>
                                <label class="label_registracija" for="spol">Spol: </label>
                                <select id="spol" name="spol" style="margin-left: 21%">
                                    <option value="M">M</option>
                                    <option value="Z">Z</option>
                                </select><br><br>
                                <label class="label_registracija" for="email">Email: </label>
                                <input type="text" id="email" style="margin-left: 18%" name="email" required="required"><br><br>
                                <input type="submit" class="button" name="gumb_registracija" value=" Registriraj ">
                                <input type="reset" class="button" style="margin-left: 24%" value=" Inicijaliziraj "><br><br>
                                <?php echo $ispis_greske; ?>
                        </form>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#korime").focusout(function () {
                                    var korIme = $("#korime").val();
                                    $.ajax({
                                        url: 'provjera_korime.php',
                                        method: 'POST',
                                        data: {korime: korIme},
                                        success: function (podatak) {
                                            if (korIme.length <= 0){
                                                $("#korime").attr('class', 'promjenaNetocno');
                                            }
                                            else if (podatak === "Postoji_korime"){
                                                alert("Korisničko ime već postoji u bazi!");
                                                $("#korime").attr('class', 'promjenaNetocno');
                                            }
                                            else if (podatak === "Nepostoji_korime"){
                                                $("#korime").attr('class', 'promjenaTocno');
                                            }
                                        }
                                    });
                                });
                                $("#potvrdaLozinke").focusout(function (){
                                   var potvrdaLozinke = $("#potvrdaLozinke").val();
                                   var lozinka = $("#lozinka").val();
                                   if (lozinka.length > 0){
                                       if (potvrdaLozinke !== lozinka){
                                           alert("Unijeli ste krivu lozinku!");
                                           $("#potvrdaLozinke").attr('class', 'promjenaNetocno');

                                       }
                                       else{
                                           $("#potvrdaLozinke").attr('class', 'promjenaTocno');
                                       }
                                   }
                                });
                                $("#email").focusout( function(){
                                    var email = $("#email").val();
                                    var regExEmail = new RegExp(/(?=^.{10,30}$)^[a-zA-Z0-9]+[.]?[a-zA-Z0-9]+@[a-zA-Z0-9]+[.]{1}[\w~$&+,:\-;=?@#|'<>.^*()%!\\/]{2,}$/);
                                    if (regExEmail.test(email)){
                                        $("#email").attr('class', 'promjenaTocno');
                                    }
                                    else{
                                        $("#email").attr('class', 'promjenaNetocno');
                                    }
                                });
                            });
                        </script>
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