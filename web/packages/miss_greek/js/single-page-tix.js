$(function(){
    var _hash = decodeURIComponent(window.location.search.split('=')[1]),
        _qrEl = document.getElementById('qrcode');
    // render the QR code
    new QRCode(document.getElementById('qrcode'), {
        text: _qrEl.getAttribute('data-target') + _hash,
        width: 300,
        height: 300
    });
});