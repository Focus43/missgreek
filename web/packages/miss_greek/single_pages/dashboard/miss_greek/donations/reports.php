<?php /** @var $donationObj MissGreekDonation */ ?>

<div id="ccm-dashboard-result-message" class="ccm-ui">
    <?php Loader::packageElement('flash_message', 'miss_greek', array('flash' => $flash)); ?>
</div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Miss Greek Donations'), t('Edit'), 'span12', false, array(Page::getByPath('/dashboard/miss_greek/donations')), Page::getByPath('/dashboard/miss_greek/donations') ); ?>

    <div id="mg-dashboard">
        <div class="ccm-pane-body ccm-pane-body-footer">
            <div class="row-fluid">
                <div class="span12">
                    <div class="well well-small">
                        <div id="chart-timeline" style="min-height:325px;width:100%;max-width:100%;"></div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div id="chart-pie" style="min-height:300px;width:100%;max-width:100%;"></div>
                </div>
                <div class="span6">
                    <div id="chart-scatter" style="min-height:300px;width:100%;max-width:100%;"></div>
                </div>
            </div>
        </div>
    </div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>

<script type="text/javascript">
    $(function(){
        // Time series
        var _timeSeries = {
            chart: {
                zoomType: 'x',
                spacingRight: 20
            },
            title: {
                text: 'Donations By Date'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: 'Amount'
                },
                min:0
            },
            tooltip: {
                shared: true
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'Total',
                data: [/* appended via ajax */]
            }]
        };

        // query time series data
        $.getJSON('<?php echo MISSGREEK_TOOLS_URL; ?>dashboard/donations/reports/time_series', function(_payload){
            $.each(_payload, function(idx, data){
                _timeSeries.series[0].data.push([Date.UTC(+data.year, +data.month, +data.day), +data.amount]);
            });
            $('#chart-timeline').highcharts(_timeSeries);
        });


        var _pieChart = {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Percentage Raised By House'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        distance: 2,
                        format: '${point.y}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: []
            }]
        };

        $.getJSON('<?php echo MISSGREEK_TOOLS_URL; ?>dashboard/donations/reports/pie_chart', function(_payload){
            $.each(_payload, function(idx, data){
                _pieChart.series[0].data.push([data.houseName, +data.totalRaised]);
            });
            $('#chart-pie').highcharts(_pieChart);
        });

        // scatter
        var _scatterChart = {
            chart: {
                type: 'scatter',
                zoomType: 'xy'
            },
            title: {
                text: 'Donation Sums'
            },
            xAxis: {
                type: 'datetime',
                title: {
                    enabled: true,
                    text: 'Date'
                },
                startOnTick: true,
                endOnTick: true,
                showLastLabel: true
            },
            yAxis: {
                title: {
                    text: 'Amount'
                },
                min:0
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                verticalAlign: 'top',
                x: 60,
                y: 35,
                floating: true,
                backgroundColor: '#FFFFFF',
                borderWidth: 1
            },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 5,
                        states: {
                            hover: {
                                enabled: true,
                                lineColor: 'rgb(100,100,100)'
                            }
                        }
                    },
                    states: {
                        hover: {
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>Amount</b><br>',
                        pointFormat: '${point.y}'
                    }
                }
            },
            series: [{
                name: 'Donations',
                color: 'rgba(223, 83, 83, .5)',
                data: []
            }]
        };

        $.getJSON('<?php echo MISSGREEK_TOOLS_URL; ?>dashboard/donations/reports/time_series', function(_payload){
            $.each(_payload, function(idx, data){
                _scatterChart.series[0].data.push([Date.UTC(+data.year, +data.month, +data.day), +data.amount]);
            });
            $('#chart-scatter').highcharts(_scatterChart);
        });
    });
</script>