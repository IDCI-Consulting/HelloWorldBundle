// any CSS and SCSS you require will output into a single css file (app.css in this case)
import '../scss/main.scss';
import '@fortawesome/fontawesome-free/css/all.min.css';

// any JS you require will output into a single js file (app.js in this case)
import setUpModal from './src/set-up-modal';
window.setUpModal = setUpModal;