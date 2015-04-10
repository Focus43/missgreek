$(function() { 'use strict';
    window.MissGreek = (function(){

        var _toolsURI       = $('[name="tools-uri"]', 'head').attr('content'),
            $document       = $(document),
            $flatirons      = $('#flatirons'),
            $clouds         = $('#clouds'),
            $cL1            = $('#cL1'),
            $cL2            = $('#cL2'),
            $cNav           = $('#cNav'),
            $contestantDets = $('#contestantDetails'),
            $innerDetails   = $('.details-inner', $contestantDets),
            _pageCount      = $('.page', $cL2).length;



        /**
         * Handler for loading contestant's details via ajax.
         * @param _id
         */
        function showContestantDetails( _id ){
            // quick way of doing caching: if contestant was loaded previously, just show
            if( +($contestantDets.data('current_contestant')) === +(_id) ){
                // trigger animation up
                $cL1.addClass('show-contestant-details');
            }else{
                $('.inner-L2', $innerDetails).empty();
                $contestantDets.addClass('loading');
                $cL1.addClass('show-contestant-details');
                // now fetch via ajax
                $.get(_toolsURI + 'contestant_info', {contestantID: _id}, function(_html){
                    $contestantDets.removeClass('loading').data('current_contestant', _id);
                    $('.inner-L2', $innerDetails).append(_html);
                }, 'html');
            }
        }


        /**
         * On clicking a contestant...
         */
        $('.contestant', '#pageContestants').on('click', function(){
            var _id = $(this).attr('data-id');
            showContestantDetails(_id);
        });


        /**
         * When "Support With Donation!" button is clicked on a specific contestant's profile...
         */
        $contestantDets.on('click', '.do-donate', function(){
            var _id = $(this).attr('data-id');
            $('#cL1').removeClass('show-contestant-details');
            $('li', '#cNav').last().trigger('click');
            $('[id*="contestantID"]', '#donation-form').val(_id);
        });


        /**
         * Close the overlay.
         */
        $contestantDets.on('click', '.closer', function(){
            $('#cL1').removeClass('show-contestant-details');
        });


        /**
         * Parallax handler by the navigation menu.
         */
        $('li, .home-link', $cNav).on('click', function(event, triggeredByArrows){
            // Get clicked element (this) and calc the index
            var $this = $(this),
                index = $this.hasClass('home-link') ? 0 : $this.index('li');
            // Swap 'active' class on the nav menu
            if( $this.is('li') ){
                $this.addClass('active').siblings('li').removeClass('active');
                $('.page', '#cL2').eq(index).addClass('active').siblings('.page').removeClass('active');
            }
            // Adjust CSS (triggers css animations automatically)
            $flatirons.add($clouds).css({marginLeft: -(10*index)+'%'});
            $cL2.css({left:-(100*index)+'%'});
            // Adjust arrow classes
            $cL1.toggleClass('hide-arrow-left', (index === 0))
                .toggleClass('hide-arrow-right', ((index+1) === _pageCount));
            // Responsive menu: auto-close if on small device
            if( !($this.hasClass('home-link')) && $('button', $cNav).is(':visible') ){
                if( ! triggeredByArrows ){
                    $('button', $cNav).trigger('click');
                }
            }
            // If "teams", trigger masonry relayout
            if( $this.attr('data-for') === 'teams' ){
                $('section', '#pageContestants').masonry();
            }
        });


        /**
         * Parallax handler by the arrows (uses nav list as canonical reference).
         */
        $('.nav-arrows', $cL1).on('click', function(){
            var $active = $('li.active', $cNav),
                _curr   = $active.index('li'),
                _goto   = $(this).hasClass('left') ? (_curr-1) : (_curr+1);
            $('li', $cNav).eq(_goto).trigger('click', true);
        });


        /**
         * Tooltips.
         */
        $document.tooltip({
            animation: false,
            placement: 'top',
            selector: '.tipify',
            container: 'body'
        });


        /**
         * Ajaxify form handler.
         */
        $('[data-method="ajax"]').ajaxifyForm().on('ajaxify_complete', function( event, resp ){
            var $form = $(this);
            // regardless of results, try and clear any previously added .alert's
            $('.alert', $form).remove();
            // clear previous validation states too, if any
            $('.has-error', $form).removeClass('has-error');
            // handle response with an error
            if( resp.code === 0 ){
                // append error message feedback
                $form.prepend('<div class="alert alert-danger">'+resp.message+'</div>');
                // apply validations
                if( resp.invalids ){
                    $.each(resp.invalids, function(_index, selector){
                        $('[data-field="'+selector+'"]', '#pageDonate').addClass('has-error');
                    });
                }
                // scroll to top
                $('#pageDonate').scrollTop(0);
                return;
            }
            // handle success
            $('#donation-success').addClass('active').siblings('.donation-step').removeClass('active');
            $('.resp-msg', '#donation-success').append(resp.message);
            if( resp.ticketURL ){
                $('.resp-ticket', '#donation-success').show().find('a').attr('href', resp.ticketURL);
            }
        });


        /**
         * Toggle disabled state for name display method on donation form.
         */
        $('#nameDisplayMethod').on('click', 'input[type="radio"]', function(){
            var $textInput = $('input[type="text"]', '#nameDisplayMethod');
            $textInput.prop('disabled', !($(this).attr('data-custom') === 'true') );
        });


        /**
         * Toggler to show the donation form.
         */
        $('#show-donation-form').on('click', function(){
            $('#donation-form').addClass('active').siblings('.donation-step').removeClass('active');
            $.placeholder.shim();
        });


        /**
         * Auto-calc tax deductions based on donation and ticket price.
         */
        (function(){
            var $qualification = $('#ticketQualification'),
                ticketPrice    = +($qualification.attr('data-price')),
                $eligibleCalc  = $('#taxDeductionCalc'),
                $ticketOption  = $('.purchase-ticket-option', '#donation-form'),
                $donationField = $('.input-field-amount', '#donation-form'),
                _deductTicket  = false;

            $ticketOption.on('change', function(){
                $donationField.trigger('keyup');
            });

            $donationField.on('keyup', function(){
                // show whether qualifies or not for ticket
                if(+(this.value) >= ticketPrice){
                    $qualification.addClass('qualifies');
                    _deductTicket = (+$ticketOption.val() === 1);
                }else{
                    $qualification.removeClass('qualifies');
                    _deductTicket = false;
                }
                // calc deduction, influenced by if ticket yes or no
                var _calcd = Math.floor(+(this.value)-(_deductTicket ? ticketPrice : 0));
                // display deduction
                $eligibleCalc.text(_calcd >= 0 ? _calcd : 0);
            });
        })();


        var _chartOpts = {
                chart: {
                    type: 'bar',
                    backgroundColor: null,
                    spacingBottom: 30
                },
                title: {
                    text: '(Click or tap a team to view more info.)',
                    style: {
                        color: '#666',
                        fontSize: '14px'
                    },
                    margin: 0,
                    y: 5
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    useHTML: true,
                    formatter: function(){
                        return '<div class="c-name">'+this.point.name+'</div><div class="h-name">('+this.point.houseName+')</div><div class="raised">$'+Highcharts.numberFormat(this.point.y)+'</div>';
                    }
                },
                plotOptions: {
                    bar:{
                        dataLabels: {
                            enabled: true,
                            formatter: function(){
                                return '$'+Highcharts.numberFormat(this.y,0);
                            }
                        },
                        pointPadding: 0.2,
                        borderWidth: 0,
                        colorByPoint: true,
                        events: {
                            click: function( _event ){
                                showContestantDetails(_event.point.contestantID);
                            }
                        }
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        useHTML: true,
                        y: 0,
                        style: {
                            color: '#FFF',
                            padding: '4px',
                            background: '#d43f3a'
                        },
                        formatter: function(){
                            return '<div>'+this.value+'</div>';
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        enabled: false
                    },
                    labels: {
                        formatter: function(){
                            return '$' + Highcharts.numberFormat(this.value);
                        }
                    }
                },
                credits: {
                    position: {
                        align: 'right',
                        verticalAlign: 'bottom'
                    }
                }
            };


        /**
         * Lazy load highcharts and the masonry library; then initialize once available...
         * @return void
         */
        $.getScript($('[name="js-uri"]', 'head').attr('content') + 'js/app.dependencies.js', function(){
            // Init masonry
            $('section', '#pageContestants').masonry();

            // Send another query to get the results data payload, *then* initialize charts
            $.getJSON(_toolsURI + 'results_data', function( _payload ){
                if( ! _payload ){
                    $('.total', '.totalRaised').text('$0');
                    return;
                }

                // set 'series' property of the chart data
                _chartOpts.series = [{data: _payload}];
                // sum the total raised
                var _total = 0;
                $.each(_payload, function(index, obj){
                    _total += +(obj.y);
                });
                $('.total', '.totalRaised').text('$' + _total);
                // initialize chart by passing data in
                $('#resultsChart').highcharts(_chartOpts);
            });
        });


        return {

        }
    })();
});

