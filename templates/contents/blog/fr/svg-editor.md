# Comment créer et utiliser un éditeur SVG ? #
![SVG logo](/images/blog/svg-logo.png "SVG logo")

## Introdution ##

### Qu'est-ce qu'un éditeur SVG ? ###

Un éditeur SVG (Graphique Vectoriel Adaptable) décrit un ensemble de graphiques vectoriels, il est basé sur fabricJS et est spécifié par le W3C. Le format .svg permet une mise à l'échelle facile et agrandissable à l'infini sans perte de qualité.

### FabricJS comme base ###

FabricJS est une librairie JavaScript qui permet de manipuler plus facilement des canvas. Nous vous invitons à consulter la documentation (en) : http://fabricjs.com/docs/.

Nous avons donc utilisé la librairie fabricJS comme une base, pour en constituer un framework facilement manipulable et adaptable.

### Les canvas ###

La balise <canvas> permet de dessiner facilement depuis un navigateur grâce à JavaScript, elle est donc l'élément essentiel de notre éditeur SVG.

### Pourquoi un éditeur SVG ? ###

Un éditeur SVG comme celui-ci donne les moyens de :
* Partir d'un canvas de base disponible
* Configurer facilement l'éditeur
* Ajouter des plugins et des nouvelles fonctionnalités

### Quelle utilisation ? ###

Vous pouvez en quelques minutes avoir votre éditeur SVG personnalisable sans avoir à recréer chacune des fonctionnalités avec la librairie fabricJS.
Vous pouvez voir cet éditeur comme un framework de fabricJS.
Vous trouverez une version live ici : https://idci-consulting.github.io/SvgEditor/

### Exemple ###

N'importe qui peut créer un autocollant personnalisable et imprimable.


## Comment l'installer ? ##

### Avant-propos ###

Dans un premier temps, assurez vous que votre navigateur supporte ES6.
Si ce n'est pas le cas, utilisez Gulp pour compiler le JavaScript en une ancienne version avec Babel.
Vous aurez besoin de docker et docker-compose, ou node and npm along avec gulp-cli.

### Installation ###


You'll need either docker and docker-compose, or node and npm along with gulp-cli. The gulp build command create a lib/ directory with built scripts in it. You must update the data-main attribute of the requirejs script with the right path of your script.

With docker

Run the following command:

docker-compose up -d
docker exec -it svgeditor_app_1 npm install
docker exec -it svgeditor_app_1 gulp build

creéra un dossier /lib avec les nouveaux scripts compatibles avec la plupart des navigateurs.


Then browse http://localhost:8030.

On your own setup

npm install
npm install --global gulp-cli


comment transformer ses fichiers JS ?


l'interet de requirejs

nous utlisons une librairie requirejs pour (pourquoi ? pour et contre)


 <script type="text/javascript"
            data-main="src/init"
            data-configuration-variable="idciSvgEditorConfig"
            data-editor-ready-function="editorReady"
            src="assets/require.js">
        </script>

préciser comment démarerr con script (grace aà data main)
si compatible code nouveau dans le dossier src sinon il faut changer le data main pour aller le chercher dans un autre dossier
