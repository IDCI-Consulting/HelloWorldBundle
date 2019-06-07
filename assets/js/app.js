// any CSS and SCSS you require will output into a single css file (app.css in this case)
import '../scss/main.scss';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'slick-carousel/slick/slick.css';
import 'code-prettify/src/prettify.css';

// Jquery
const $ = require('jquery');
global.$ = global.jQuery = $

// any JS you require will output into a single js file (app.js in this case)
import setUpModal from './src/set-up-modal';
window.setUpModal = setUpModal;

import setUpCarousel from './src/set-up-carousel';
window.setUpCarousel = setUpCarousel;

import asideMenuHighlight from './src/aside-menu-highlight';
window.asideMenuHighlight = asideMenuHighlight;

import showAceEditor from './src/editor-show';
window.showAceEditor = showAceEditor;

import handleHeadFollowing from './src/handle-head-following'
window.handleHeadFollowing = handleHeadFollowing;

import makeSyntaxHighlighting from './src/make-syntax-highlighting'
window.makeSyntaxHighlighting = makeSyntaxHighlighting;

import slugify from './src/slugify'
window.slugify = slugify;
