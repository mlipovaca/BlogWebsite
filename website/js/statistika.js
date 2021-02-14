var dodajFunkcijeOnLoad = function(dogadaj){
      if(window.addEventListener){
          window.addEventListener('load',dogadaj,false);
      }else{
          window.attachEvent('onload',dogadaj);
      }
};

dodajFunkcijeOnLoad(grafovi);

/* -- FUNKCIJE -- */
function grafovi() {

    $.getJSON("graf_klikova.php", function (dogadaj) {
        var rezultatKlikova = [];
        for (var i = 0; i < dogadaj.length; i++) {
            rezultatKlikova.push({"label": dogadaj[i].korisnici, "y": dogadaj[i].brojKlikova});
        }

        var grafKlikova = new CanvasJS.Chart("grafKlikovaKorisnici", {
            zoomEnabled: true,
            panEnabled: true,
            theme: "light4",
            animationEnabled: true,
            title: {
                text: "Graf posjećenosti blogova"
            },
            axisX: {
                title: "Ime bloga"
            },
            axisY: {
                title: "Broj klikova",
                minimum: 0
            },
            data: [
                {
                    type: "column",
                    dataPoints: rezultatKlikova
                }
            ]
        });
        grafKlikova.render();
    });
    $.getJSON("graf_lajkova.php", function (dogadaj) {
        var rezultatLajkova = [];
        for (var i = 0; i < dogadaj.length; i++) {
            rezultatLajkova.push({"label": dogadaj[i].korisnici, "y": dogadaj[i].brojLajkova});
        }

        var grafLajkova = new CanvasJS.Chart("grafLajkovaKorisnici", {
            zoomEnabled: true,
            panEnabled: true,
            theme: "light4",
            animationEnabled: true,
            title: {
                text: "Graf lajkova blogova"
            },
            axisX: {
                title: "Ime bloga"
            },
            axisY: {
                title: "Broj lajkova",
                minimum: 0
            },
            data: [
                {
                    type: "column",
                    dataPoints: rezultatLajkova
                }
            ]
        });
        grafLajkova.render();
    });
}