Description
===

Plugin pour piloter vos comptes spotify connect depuis l'interface jeedom.

Installation
===

Créer son compte et vous connecter sur https://developer.spotify.com/dashboard/
 
Créer un client-id/client-secret ("Create a client id")

![step-1](https://barre35.github.io/jeedom-plugin-spotify/assets/images/step-1.png)

![step-2](https://barre35.github.io/jeedom-plugin-spotify/assets/images/step-2.png)

![step-3](https://barre35.github.io/jeedom-plugin-spotify/assets/images/step-3.png)

![client-secret](https://barre35.github.io/jeedom-plugin-spotify/assets/images/client-secret.png)

Editer les propriétés ("Edit settings") en ajoutant https://[adresse]:[port]/index.php?v=d&m=spotify&p=spotify ou http://[adresse]:[port]/index.php?v=d&m=spotify&p=spotify en fonction de votre installation jeedom, puis sauvegarder

![settings](https://barre35.github.io/jeedom-plugin-spotify/assets/images/settings.png)

Depuis la page de configuration du plugin, installer les dépendances, puis coller votre client-id et votre client-secret et selectionner le protocole à utiliser pour la communication du daemon avec le plugin spotify

![plugin](https://barre35.github.io/jeedom-plugin-spotify/assets/images/plugin.png)

Redémarrer le daemon du plugin

Configuration
===

Ajouter un nouvel equipement pour le plugin spotify

![equipment](https://barre35.github.io/jeedom-plugin-spotify/assets/images/equipment.png)

Cliquer sur le bouton tokenize, sasir votre login/password et approuver

![accept](https://barre35.github.io/jeedom-plugin-spotify/assets/images/accept.png) 

Les champs sont automatiquement remplis et il ne vous reste qu'à sauvegarder

![save](https://barre35.github.io/jeedom-plugin-spotify/assets/images/save.png)

Redémarrer le daemon pour prendre en compte le nouvel équipement

Utilisation
===

Pour chaque equipement activé vous aurez un widget sur votre dashboard
 
![Widget](https://barre35.github.io/jeedom-plugin-spotify/assets/images/widget.png)


