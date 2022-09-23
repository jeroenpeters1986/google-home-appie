# Appie voor je Google Home
Direct een boodschap toevoegen aan je Albert Heijn boodschappen lijstje met één 
commando. "_Hey Google: boodschap, kaas_"

# 😭 Helaas, pindakaas! 🍯
⚠ Sinds april 2021 heeft Albert Heijn zijn API veranderd, de functionaliteit voor het toevoegen van boodschappen zonder dat je er direct een product bij hebt zit nog wel in de app, maar niet meer in de site. Het is daarom lastig om deze functionaliteit te herstellen. Het lijkt andere developers ook nog niet gelukt te zijn. Heel jammer, maar voor nu heb je dus niets (meer) aan die script.

---

<img src="https://content.presspage.com/uploads/1241/500_ah-logo-232800.jpg" height="96"> <img src="https://cdn2.techadvisor.co.uk/cmsdata/features/3663037/google_home_mini_chalk.jpg" height="120"> <img src="http://resources.mynewsdesk.com/image/upload/c_limit,dpr_2.625,f_auto,h_700,q_auto,w_360/mu5okq33jmyrjms0kddt.jpg" height="96"> <img src="https://www.php.net/images/logos/new-php-logo.png" height="80">


## Vereisten
 * Openbare webserver
 * PHP 5.6+
 * Schrijfrechten voor de webserver op de directory waar het bestand in staat.


## Installatie
Zorg ervoor dat je `appie.php` op een bereikbare plaats op het internet zet. Pas
ook in het bestand nog de gegevens aan van je account, dat doe je op de regels 13 en 14.

Roep het bestand na installatie eenmalig zelf aan, om de cookie te laten instellen.
Bijvoorbeeld: https://voorbeeld.nl/appie.php?boodschap=eerste_run


### Oktober 2020 Update: reCaptcha Extra stap vereist
In 2020 heeft Albert Heijn de Google reCaptcha geïmplementeerd in hun login pagina 
(net als de bol.com inlogknop). Daardoor is er iets meer werk nodig om het script 
nu aan de praat te krijgen, maar daarna kun je er weer heel erg lang tegenaan.

1. Na het handmatig aanroepen wordt `ah.cookie` aangemaakt
2. Log daarna zelf in op ah.nl met je browser (bijvoorbeeld Chrome)
3. Bekijk de cookies met de tools in je browser (zie ook https://developers.google.com/web/tools/chrome-devtools/storage/cookies )
4. Kopieer dan de waardes van deze cookies en update het bestand `ah.cookie` daarmee
   * SSLB
   * TS0163d06f
   * TS01fb4f52
   * ah_token_presumed


## Beschikbaar maken via IFTTT
 * Registreer je als ontwikkelaar op https://platform.ifttt.com/
 * Als je dit hebt gedaan, maak je eerst een Service aan (https://platform.ifttt.com/services/new)
 * Maak daarna een nieuwe Applet 
   * Begin met Google Assistant als trigger
   * Kies dan voor "Say a phrase with a text ingredient"
   * Vul dan in wat je wilt zeggen, zelf gebruik ik "Appie $"
     * Je activeert dit dan door te zeggen: "_Hey Google.. Appie, hagelslag_"
   * Vul eventueel nog een alternatief woord in. "Boodschap" werkt vaak niet goed in 
     verband met het ingebouwde boodschappenlijstje van Google Home
   * Vul een response in, bijvoorbeeld:
     * _Ik heb $ op de boodschappenlijst gezet_ 
   * Voeg dan een Action toe
   * Kies bij Action voor de Webhooks, en dan voor "Make a web request"
   * Vul bij de value dan het adres in waar je appie.php hebt staan, gevolgd door 
     de opdracht voor het textingrient. Staat je bestand op 'jeroenpeters.dev', dan
     ziet dit er zo uit:
     * `https://jeroenpeters.dev/appie.php?boodschap={{TextField}}`
   * Kies voor de HTTP-Methode `GET` bij de volgende vraag
   * De request is er eentje van `application/json`
   * Geef de Applet nog een titel en je kunt deze connecten met je Google Home, 
     net zoals je dat misschien al met andere services zoals je Hue of andere 
     service gedaan hebt.
 * Roep "Hey Google, Appie water" en het zal op je Appie lijstje verschijnen.
     
Veel plezier en gemak van deze Appie helper!

Heb je nog vragen/opmerkingen, laat het gerust weten via https://jeroenpeters.dev/contact/


##### Disclaimer
Deze oplossing is geen officieel onderdeel van Albert Heijn, maar gebruikt alleen 
de website functionaliteit van Albert Heijn om de acties uit te voeren.

Ik heb het gemaakt omdat Albert Heijn zelf een gelijke Applet weer heeft ingetrokken.
