/**
 * Created by brahim on 28/05/15.
 */
var focusFieldEffect =function() {
    // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
    if (!String.prototype.trim) {
        (function() {
            // Make sure we trim BOM and NBSP
            var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
            String.prototype.trim = function() {
                return this.replace(rtrim, '');
            };
        })();
    }

    [].slice.call(document.querySelectorAll('.input__field')).forEach(
        function(inputElement) {
            // events:
            inputElement.addEventListener('focus', onInputFocus);
            inputElement.addEventListener('blur', onInputBlur);
        }
    );

    function onInputFocus(e) {
        classie.add(e.target.parentNode, 'input--filled');
    }

    function onInputBlur(e) {
        if (e.target.value.trim() === '') {
            classie.remove(e.target.parentNode, 'input--filled');
        }
    }
};


focusFieldEffect();
