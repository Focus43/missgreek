$(function(){
    window.DonationTestHelper = new function(){

        var _self = this,
            $form = $('#donation-form');

        function randCharString( _length ){
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for( var i=0; i < _length; i++ ){
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        }

        this.autofill = function(){
            // contestant
            var $selectEl      = $('[id*="contestantID"]', $form),
                randChildIndex = Math.floor( (Math.random() * $('option', $selectEl).length) + 1 );
            randChildIndex = (+(randChildIndex) === 1) ? 2 : randChildIndex;
            $selectEl.val( $('option', $selectEl).eq(randChildIndex).val() );
            // first + last name
            $('[id*="firstName"]', $form).val( randCharString(Math.floor(Math.random()*10)) );
            $('[id*="lastName"]', $form).val( randCharString(Math.floor(Math.random()*10)) );
            // email
            $('[id*="email"]', $form).val( randCharString(Math.floor(Math.random()*10)) + '@' + randCharString(Math.floor(Math.random()*10)) + '.com' );
            // address stuff
            $('[id*="address1"]', $form).val( randCharString(Math.floor(Math.random()*10)) );
            $('[id*="address2"]', $form).val( randCharString(Math.floor(Math.random()*10)) );
            $('[id*="addressCity"]', $form).val( randCharString(Math.floor(Math.random()*10)) );
            $('[id*="addressState"]', $form).val('WY');
            $('[id*="addressZip"]', $form).val('12345');
            // card details
            $('[id*="ccNumber"]', $form).val('4007000000027');
            $('[id*="ccType"]', $form).val('visa');
            $('[id*="ccExpMo"]', $form).val( Math.floor(Math.random() * 11) );
            $('[id*="ccExpYr"]', $form).val('2015');
            // credit card amount
            $('[id*="amount"]', $form).val(Math.floor(Math.random() * 500));

            return _self;
        }

        this.send = function(){
            $form.trigger('submit');

            return _self;
        }
    };
});