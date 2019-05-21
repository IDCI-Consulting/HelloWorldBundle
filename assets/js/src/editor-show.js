import { fetch as fetchPolyfill } from 'whatwg-fetch';
import ace from 'brace';

// import different mode for Ace
import 'brace/mode/php';
import 'brace/mode/javascript';
import 'brace/mode/twig';

// import different theme for Ace
import 'brace/theme/ambiance';
import 'brace/theme/monokai';
import 'brace/theme/tomorrow_night'
import 'brace/theme/chaos';
import 'brace/theme/twilight';
import 'brace/theme/cobalt';
import 'brace/theme/clouds_midnight';
import 'brace/theme/dreamweaver';


export default function showAceEditor(content) {
    var timer;
    var showText = function (editor, snippet, index) {
        if (index < snippet.length) { // insert at cursor
            editor.getSession().insert(editor.getCursorPosition(), snippet[index++]);

            // clear selection just in case there was something selected
            editor.selection.clearSelection();

            // make sure cursor is visible
            editor.renderer.scrollCursorIntoView();

            timer = setTimeout(function () {
                showText(editor, snippet, index);
            }, 50);
        }
    };

    var getFileRaw = function (editor) { // Clear the timeout callback
        clearTimeout(timer);

        // Clear the editor
        editor.setValue("");


        var idciCodeWriter = JSON.parse(content);

        // get a random file object
        var file = idciCodeWriter.files[Math.floor(Math.random() * idciCodeWriter.files.length)];

        var init = {
            method: 'GET'
        };

        fetchPolyfill(file.url, init).then(function (response) {
            return response.text();
        }).then(function (fileRaw) {
            var content = fileRaw;

            editor.getSession().setMode('ace/mode/'+file.language);
            editor.setTheme('ace/theme/'+file.theme);
            showText(editor, content, 0);
        }).catch(function (error) {
            showText(editor, '//{% trans %}Write your code here{% endtrans %}!!!');
        });
    };

    document.addEventListener("DOMContentLoaded", function (event) {
        var editor = ace.edit("editor");
        ace.config.set("basePath", "Scripts/ace");
        // Disable warn message in console. Advised by Ace developers.
        editor.$blockScrolling = Infinity;

        var options = {
            animatedScroll: true,
            autoScrollEditorIntoView: true,
            fontSize: 10,
            mode: "ace/mode/php",
            scrollSpeed: 1,
            showFoldWidgets: false,
            showPrintMargin: false,
            theme: "ace/theme/dreamweaver"
        };

        editor.setOptions(options);

        getFileRaw(editor);

        // Call getFIleRaw every 2 minutes
        setInterval(function () {
            getFileRaw(editor);
        }, 120000);
    });
};