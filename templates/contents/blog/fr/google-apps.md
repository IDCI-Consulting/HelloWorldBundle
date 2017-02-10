# Les Google Apps pour votre domaine
![Google apps logo](/images/blog/google-apps_logo.jpg "Google Apps")

## Qu'est ce que Google Apps ?

Google Apps, c'est l'ensemble des services en ligne Google tel que Gmail (Messagerie), Google Calendar (Agenda), Google Doc (Documents)...
en "Marque Blanche" au service de votre groupe, association, entreprise, établissement, Etat, etc.

Votre type d'organisation détermine le niveau de service que vous allez utiliser, c'est-à-dire gratuit, ou payant.

La solution de messagerie électronique Gmail de Google est un outil de grande qualité qui fait preuve
de beaucoup d'avantages face aux solutions techniques équivalentes existantes aujourd'hui.

C'est donc logiquement que nous proposons la mise en place de cette solution pour nos clients.

Comment créer prenom.nom@monentreprise.fr et utiliser le tout grâce à Gmail ?

La suite de cet article vous explique comment mettre en place les **Google Apps** pour votre domaine, vous allez voir, pas besoin d'être un grand informaticien,
il vous suffit d'avoir un ordinateur, une connexion internet et quelques précieuses minutes.

Si vous ne savez pas quel domaine utiliser et bien réfléchissez-y !
Pour les autres, partons du principe que vous l'avez déjà souscrit auprès d'un Registar,
nous allons illustrer nos exemples avec le domaine suivant: monentreprise.fr


## Créer un compte Google Apps

Google Apps for Business, Education, Government ou Non profits.
Rendez-vous ici : [ Google Apps]( http://www.google.com/apps/intl/fr/group/index.html)

![Renseignez votre nom de domaine](/images/blog/google-apps_dns.png)

Renseignez votre nom de domaine dans le champ suivant.

![Inscription aux Google Apps](/images/blog/google-apps_registration.png)

Vous pouvez maintenant vous inscrire.
Renseignez les champs demandés, pour le moment il n'y a rien de technique ;)


## Valider l'appartenance du domaine (plusieurs méthodes possibles)

![Edition du champ DNS TXT](/images/blog/ovh_dns_txt.png "Edition du champ DNS TXT")

DNS TXT, afin de prouver à Google que le domaine vous appartient.
//Ici, une capture d'écran vous montrant ou modifier votre champ DNS TXT avec le registar OVH.


## Configuration de la messagerie

!["Edition du champ DNS MX"](/images/blog/ovh_dns_mx.png "Edition du champ DNS MX")

DNS MX : Mise à jour du [DNS MX](http://fr.wikipedia.org/wiki/Domain_Name_System#MX_record) auprès de votre Registar.

La page d'ajout d'un champ DNS MX se présente ainsi sous OVH :

![Champs DNS MX](/images/blog/ovh_zone_dns.png "Champs DNS MX")

Ajoutez les champs suivants :

| Priority | Destination |
|----------|--------------
| 10 | ASPMX.L.GOOGLE.COM.
| 20 | ALT1.ASPMX.L.GOOGLE.COM.
| 20 | ALT2.ASPMX.L.GOOGLE.COM.
| 30 | ASPMX2.GOOGLEMAIL.COM.
| 30 | ASPMX3.GOOGLEMAIL.COM.
| 30 | ASPMX4.GOOGLEMAIL.COM.
| 30 | ASPMX5.GOOGLEMAIL.COM.


## Configuration de l'accès à la messagerie (mail.monentreprise.fr)

![Champs DNS CNAME](/images/blog/ovh_dns_cname.png "Champs DNS CNAME")

Création d'un alias mail.monentreprise.fr sous forme de champ DNS CNAME vers ghs.google.com.

![Tableau de bord](/images/blog/google-apps_dashboard.png "Tableau de bord")

Voici notre tableau de bord, il permet de paramétrer toute la suite Google Apps.

![Parametrage des emails](/images/blog/google-apps_params_email.png "Parametrage des emails")

Rendez vous dans l'onglet "Paramètres", dans le service "Email" puis sur "Modifier l'URL"

![Parametrage des emails](/images/blog/google-apps_webmail_url.png "Parametrage des emails")

Nous pouvons maintenant transformer l'URL de nos e-mails.

Par défaut : **mail.google.com/a/monentreprise.fr** qui est équivalent à : **mail.monentreprise.fr** plus facile à retenir.

Enregistrez et le tour est joué !


## Vérification des champs DNS

Sous un environnement Linux, il est possible d'accéder aux enregistrements DNS de n'importe quel domaine via la commande **dig**.

<p class="notice question">
NOTE: Sous debian (ubuntu) et dérivés pour utiliser la commande dig
</p>

Il vous suffit d'installer le paquet dnsutils.


### Enregistrements MX ###

```
    $ dig monentreprise.fr MX

    ; <<>> DiG 9.7.3 <<>> monentreprise.fr MX
    ;; global options: +cmd
    ;; Got answer:
    ;; ->>HEADER< ;; flags: qr rd ra; QUERY: 1, ANSWER: 8, AUTHORITY: 2, ADDITIONAL: 0

    ;; QUESTION SECTION:
    ;monentreprise.fr. IN MX

    ;; ANSWER SECTION:
    monentreprise.fr. 86400 IN MX 5 alt2.aspmx.l.google.com.
    monentreprise.fr. 86400 IN MX 10 aspmx2.googlemail.com.
    monentreprise.fr. 86400 IN MX 10 aspmx3.googlemail.com.
    monentreprise.fr. 86400 IN MX 10 aspmx4.googlemail.com.
    monentreprise.fr. 86400 IN MX 10 aspmx5.googlemail.com.
    monentreprise.fr. 86400 IN MX 1 aspmx.l.google.com.
    monentreprise.fr. 86400 IN MX 1 redirect.ovh.net.
    monentreprise.fr. 86400 IN MX 5 alt1.aspmx.l.google.com.

    ;; AUTHORITY SECTION:
    monentreprise.fr. 86400 IN NS dns19.ovh.net.
    monentreprise.fr. 86400 IN NS ns19.ovh.net.

    ;; Query time: 54 msec
    ;; SERVER: 212.27.40.241#53(212.27.40.241)
    ;; WHEN: Sun Jul 10 17:58:20 2011
    ;; MSG SIZE rcvd: 281
```


### Enregistrements TXT

```
    $ dig monentreprise.fr TXT

    ; <<>> DiG 9.7.3 <<>> monentreprise.fr TXT
    ;; global options: +cmd
    ;; Got answer:
    ;; ->>HEADER< ;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 2, ADDITIONAL: 0

    ;; QUESTION SECTION:
    ;monentreprise.fr. IN TXT

    ;; ANSWER SECTION:
    monentreprise.fr. 86400 IN TXT "google-site-verification=xxxxxxxxxxxxxxxxxxxxxx..."

    ;; AUTHORITY SECTION:
    monentreprise.fr. 86400 IN NS dns19.ovh.net.
    monentreprise.fr. 86400 IN NS ns19.ovh.net.

    ;; Query time: 57 msec
    ;; SERVER: 212.27.40.241#53(212.27.40.241)
    ;; WHEN: Sun Jul 10 18:01:02 2011
    ;; MSG SIZE rcvd: 158
```


### Enregistrements CNAME

```
    $ dig mail.monentreprise.fr CNAME

    ; <<>> DiG 9.7.3 <<>> mail.monentreprise.fr CNAME
    ;; global options: +cmd
    ;; Got answer:
    ;; ->>HEADER< ;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 2, ADDITIONAL: 0

    ;; QUESTION SECTION:
    ;mail.monentreprise.fr. IN CNAME

    ;; ANSWER SECTION:
    mail.monentreprise.fr. 86400 IN CNAME ghs.google.com.

    ;; AUTHORITY SECTION:
    monentreprise.fr. 86400 IN NS ns19.ovh.net.
    monentreprise.fr. 86400 IN NS dns19.ovh.net.

    ;; Query time: 28 msec
    ;; SERVER: 212.27.40.241#53(212.27.40.241)
    ;; WHEN: Sun Jul 10 18:03:12 2011
    ;; MSG SIZE rcvd: 110
```

Il ne vous reste plus qu'à franchir [les grades Ninja Gmail](http://www.google.com/mail/help/tips.html) !

!["Ninjas Gmail"](/images/blog/ninjas-sprited.jpg)

Si vous avez une question propos des **Google Apps**, vous pouvez [nous contacter](http://www.idci-consulting.fr/contact "Contactez-nous").
