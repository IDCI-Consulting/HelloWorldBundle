// any CSS and SCSS you require will output into a single css file (app.css in this case)
import '../scss/main.scss';
import '@fortawesome/fontawesome-free/css/all.min.css';
<<<<<<< HEAD
import 'slick-carousel/slick/slick.css';
import 'code-prettify/src/prettify.css';
import 'animate.css';
=======
import 'slick-carousel/slick/slick.scss';
import 'slick-carousel/slick/slick-theme.scss';

// Jquery
>>>>>>> Rework the docker stack

// Jquery
const $ = require('jquery');
global.$ = global.jQuery = $;

// any JS you require will output into a single js file (app.js in this case)
import setUpModal from './src/set-up-modal';
window.setUpModal = setUpModal;

import setUpCarousel from './src/set-up-carousel';
window.setUpCarousel = setUpCarousel;

import setUpWow from './src/set-up-wow';
window.setUpWow = setUpWow;

import asideMenuHighlight from './src/aside-menu-highlight';
window.asideMenuHighlight = asideMenuHighlight;

import showAceEditor from './src/editor-show';
window.showAceEditor = showAceEditor;

import handleHeadFollowing from './src/handle-head-following';
window.handleHeadFollowing = handleHeadFollowing;

import handleScroll from './src/handle-scroll';
window.handleScroll = handleScroll;

import makeSyntaxHighlighting from './src/make-syntax-highlighting';
window.makeSyntaxHighlighting = makeSyntaxHighlighting;

import slugify from './src/slugify';
window.slugify = slugify;
