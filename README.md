##Storytelling Author Mapillary BAsed

L’applicativo permette di scegliere una qualunque area del mondo, per nome o indirizzo, visualizzare su una mappa quelle che sono 
le tracce delle sequenze di immagini di Mapillary che sono disponibili per quell’area e, sulla base di queste, interattivamente, 
di impostare quelli che sono i punti di interesse per cui si vuole che il nostro percorso passi (sino ad un massimo di 25 punti …. ).

[<img src="https://cesaregerbino.files.wordpress.com/2016/09/samba.png">](http://www.cesaregerbino.com/Mapillary/SAMBA/step-1.php)

L’applicativo calcolerà il percorso ed individuerà quelle che sono le immagini di Mapillary più vicine ai punti del percorso stesso (nel verso della percorrenza … ), e proverà a proporre una prima versione dello storytelling di cui l’utente può fare il preview in una sorta di “filmato”.

Se il preview è giudicato soddisfacente l’utente può scaricare il codice sorgente HTML / Javascript per una sua esecuzione autonoma o per poter essere incluso nella pagina web del proprio sito.

Qualora nel preview vi siano immagini non corrette o che generano problemi nell’esecuzione del “filmato” stesso (situazione piuttosto comune …), o qualora l’utente desideri inserire dei piccoli commenti testuali in corrispondenza di alcune delle immagini, è possibile effettuare un raffinamento, deselezionando le immagini da escludere dalla restituzione e/o editando il testo.

Nuovamente sarà possibile effettuare un preview e/o scaricare il codice sorgente HTML / Javascript: il processo di raffinamento può essere eseguito più volte sino ad arrivare alla configurazione di gradimento.

Ogni passo è corredato da un help ed un filmato di esempio di utilizzo.

I vincoli per l’utilizzo sono i seguenti:

* utilizzo di un browser di recente generazione e che supporti WebGL
* numero massimo punti per il calcolo percorso: 25
* non si possono usare lettere o caratteri speciali nel testo descrittivo che viene aggiunto all’immagine. Non è possibile usare tag HTML (es. per mettere link)

Esistono problemi noti, che sono:

* il servizio di routing di MapBox ha un piccolo bug per cui, a volte, non riporta nel percorso l’ultimo punto (destinazione). Ho cercato di aprire una richiesta su [StackExchange] (http://gis.stackexchange.com/questions/209012/mapbox-directions-with-more-than-2-waypoints-bug-or-my-error),  ma ad oggi non ho ancora avuto risposte, nemmeno da MapBox stessa. Il bug non è comunque bloccante anche se fastidioso

Ecco alcuni esempi di storytelling prodotti con SAMBA:

* [Area archeologica Pompei](http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/Pompei.html)
* [Parco della Città di Brasilia] (http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/Brasilia.html)
* [Giardini Luxembourg di Parigi] (http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/ParisLuxembourg.html)
* [Percorso pedonale / panoramico a Genova]  (http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/Genova-Sturla-Vernazzola-Boccadasse-With-Text.html)


## Architettura
Schematicamente l’architettura della soluzione è la seguente:

![alt tag](https://cesaregerbino.files.wordpress.com/2016/09/architecture.png)

Per implementare le sue funzionalità utilizza una serie di librerie Javascript e di alcuni servizi e precisamente:

* [mapbox-gl-draw.js] (https://github.com/mapbox/mapbox-gl-draw): fornisce il supporto per disegnare ed editare features su mappe basate su MapBox GL JS
* [Mapillary API] (http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/Brasilia.html): le API di accesso alle immagini e sequenze di Mapillary
* [MapBox Directions API] (https://www.mapbox.com/api-documentation/#directions): le API MapBox per il calcolo dei percorsi
* [MapBox GL Geocoder] (http://www.cesaregerbino.com/Mapillary/SAMBA/Examples/ParisLuxembourg.html): il Geocoder per mappe basate su MapBox GL JS
* [Turf.JS] (http://turfjs.org/): libreria Javascript realizzata da MapBox per il calcolo di funzioni geospaziali direttamente sul browser
* il servizio (vector tiles), delle sequenze di Mapillary  pubblkicato su http://d25uarhxywzl1j.cloudfront.net/v0.1/{z}/{x}/{y}.mvt


## Note per l'utilizzo del codice

E' necessario essere dotati delle chiavi per l'utilizzo delle API MapBox e Mapillary che devono essere inserite del file settings.php


## Riferimenti e licenza d'uso

Il codice dell'applicazione e' disponibile con licenza [Licenza MIT] (https://opensource.org/licenses/MIT) Copyright (c) [2014] - rif. [https://it.wikipedia.org/wiki/Licenza_MIT] (https://it.wikipedia.org/wiki/Licenza_MIT)
