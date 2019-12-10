# Appie voor je Google Home
Direct een boodschap toevoegen aan je Albert Heijn boodschappen lijstje met één 
commando. "_Hey Google: boodschap, kaas_"

<img src="https://content.presspage.com/uploads/1241/500_ah-logo-232800.jpg" height="96"> <img src="https://cdn2.techadvisor.co.uk/cmsdata/features/3663037/google_home_mini_chalk.jpg" height="120">

## Installatie
Zorg ervoor dat je `appie.php` op een bereikbare plaats op het internet zet. Pas
ook in het bestand nog de gegevens aan van je account, dat doe je op de regels 16 en 17.

## Beschikbaar maken via IFTTT
 * Registreer je als ontwikkelaar op https://platform.ifttt.com/
 * Als je dit hebt gedaan, vul de Service pagina in
 * Maak een nieuwe Applet 
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
     de opdracht voor het textingrient. Staat je bestand op jeroenpeters.dev, dan
     ziet dit er zo uit:
     * `https://jeroenpeters.dev/appie.php?boodschap={{TextField}}`
     * Let op dat je `?boodschap=` niet aanpast
   * Kies voor de HTTP-Methode `GET` bij de volgende vraag
   * De request is er eentje van `application/json`
   * Geef de Applet nog een titel en je kunt deze connecten met je Google Home, 
     net zoals je dat misschien al met andere services zoals je Hue of andere 
     service gedaan hebt.
     
Veel plezier en gemak van deze Appie helper!

Heb je nog vragen/opmerkingen, laat het weten via https://jeroenpeters.dev/contact/
