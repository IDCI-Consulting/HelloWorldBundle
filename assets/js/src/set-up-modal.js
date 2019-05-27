import Swal from 'sweetalert2'
import { fetch as fetchPolyfill } from 'whatwg-fetch';

export default function setUpModal(event) {
    event.preventDefault();
    
    var init = {
        method: 'GET',
        dataType: 'html',
        headers: {
            'Content-Type': 'text/html',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    fetchPolyfill(this.getAttribute('href'), init).then(function (response) {
        return response.text();
    }).then(function (text) {
        console.log(text);
        Swal.fire({
            title: 'Contact',
            html: text,
            showConfirmButton: false,
            showCloseButton: true
        });
    });
}