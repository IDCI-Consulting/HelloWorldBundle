// any CSS and SCSS you require will output into a single css file (app.css in this case)
import '../scss/main.scss';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'slick-carousel/slick/slick.scss';
import 'slick-carousel/slick/slick-theme.scss';

// Jquery 

const $ = require('jquery');
window.$ = $;

// any JS you require will output into a single js file (app.js in this case)
import setUpModal from './src/set-up-modal';
window.setUpModal = setUpModal;

import asideMenuHighlight from './src/aside-menu-highlight';
window.asideMenuHighlight = asideMenuHighlight;

import showAceEditor from './src/editor-show';
window.showAceEditor = showAceEditor;

import setUpActivitiesSlick from './src/set-up-activities-slick';
window.setUpActivitiesSlick = setUpActivitiesSlick;