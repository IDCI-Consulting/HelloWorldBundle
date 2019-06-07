require('code-prettify');

export default function makeSyntaxHighlighting() {
    document.querySelectorAll('code').forEach(function (code) {
        code.classList.add('prettyprint');
    });
    PR.prettyPrint();
}