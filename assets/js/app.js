/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)

import '../css/app.css';

// any SCSS you require will output into a single scss file

import '../scss/main.scss';

// any JS you require will output into a single js file (app.js in this case)
require('../js/modernizr');
require('../js/accordionHandler/accordion');
require('../js/formFieldsEffects/classie');
require('../js/formFieldsEffects/focusFieldEffect');
require('../js/headMouseTracker/HeadImage');
require('../js/headMouseTracker/getMousePosition');
require('../js/headMouseTracker/initImages');
require('../js/headerHandler/animateHamburgerMenu');
require('../js/headerHandler/navHandler');
require('../js/headerHandler/scrollHandler');
require('../js/idciScripts/articleNavBuilder');
require('../js/idciScripts/end');
require('../js/idciScripts/flashMessages');
require('../js/idciScripts/scrollTo');
require('../js/idciScripts/slick');
require('../js/idciScripts/tabs');
require('../js/init/init');
require('../js/polyfills/rem');

// Images 

const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
imagesContext.keys().forEach(imagesContext);

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');