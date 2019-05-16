import Swal from 'sweetalert2'
import { fetch as fetchPolyfill } from 'whatwg-fetch';

export default function setUpModal(event) {
    event.preventDefault();
    
    var init = {
        method: 'GET',
        dataType: 'text/html',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    fetchPolyfill(this.getAttribute('href'), init).then(function (response) {
        return response.text();
    }).then(function (text) {
        Swal.fire({
            title: 'Contact',
            html: text,
        });
    });
}