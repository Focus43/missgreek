$(function(){
    var _qrEl = document.getElementById('loginQrScanner');
    if( _qrEl ){
        new QRCode(_qrEl, {
            text: _qrEl.getAttribute('data-target'),
            width: 250,
            height:250
        });
    }
});