<!DOCTYPE HTML>
<html lang="<?php echo LANGUAGE; ?>">
<head>
<!-- ****************************** HEY YOU! *******************************
Enjoy checking out what's under the hood? You're probably a nerd, and we
love nerds... so we should talk. http://focus-43.com
************************************************************************ -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php Loader::element('header_required'); // REQUIRED BY C5 // ?>
</head>

<body>
    <!-- start out hiding the left arrow, since on page load thats the default -->
    <div id="cL1" class="hide-arrow-left">
        <!-- flatirons parallax background -->
        <div id="flatirons"></div>
        <!-- transparent clouds -->
        <div id="clouds"></div>
        <!-- navigation arrows -->
        <a class="nav-arrows left"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="nav-arrows right"><span class="glyphicon glyphicon-chevron-right"></span></a>

        <!-- nav -->
        <div id="cNav" class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#gg-bs-nav">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="home-link navbar-brand visible-xs"><span class="glyphicon glyphicon-home"></span> CU Greek Gods</a>
                </div>

                <div id="gg-bs-nav" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="hidden-xs active"><a><span class="glyphicon glyphicon-home"></span></a></li>
                        <li><a>About</a></li>
                        <li data-for="teams"><a>Teams</a></li>
                        <li><a>Results</a></li>
                        <li><a>Donate / Buy Ticket</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="cL2">
            <!-- intro -->
            <div id="pageIntro" class="page active">
                <div class="wrap">
                    <div class="inner">
                        <div class="content">
                            <section class="max-800">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php $a = new Area('Home Content'); $a->display($c); ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pageAbout" class="page">
                <div class="wrap">
                    <div class="inner">
                        <div class="content">
                            <section class="white max-800">
                                <?php $a = new Area('About'); $a->display($c); ?>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <!-- teams -->
            <div id="pageContestants" class="page">
                <div class="wrap">
                    <div class="inner">
                        <div class="content">
                            <section>
                                <?php foreach($contestantList AS $contestantObj){ /** @var MissGreekContestant $contestantObj */ ?>
                                    <div class="contestant" data-id="<?php echo $contestantObj->getContestantID(); ?>">
                                        <div class="c-inner">
                                            <img src="<?php echo $imageHelper->getThumbnail($contestantObj->getFeaturedPhotoObj(), 350, 350, true)->src; ?>" />
                                            <div class="caption">
                                                <div class="tbl">
                                                    <div class="middlizer">
                                                        <h4><?php echo $contestantObj; ?></h4>
                                                        <span><?php echo $contestantObj->getHouseName(); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <!-- results -->
            <div id="pageResults" class="page">
                <div class="wrap">
                    <div class="inner">
                        <div class="content">
                            <section class="white" style="max-width:1000px;">
                                <h4 class="totalRaised">Total Raised To Date: <span class="total">...</span></h4>
                                <div id="resultsChart"></div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <!-- donations -->
            <div id="pageDonate" class="page">
                <div class="wrap">
                    <div class="inner">
                        <div class="content">
                            <section class="white max-800">
                                <!-- first page shown -->
                                <div id="donation-pre" class="donation-step active">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?php $a = new Area('Donate Language'); $a->display($c); ?>
                                            <blockquote>A minimum contribution of $<?php echo $ticketPrice; ?> is required for a ticket to the event.</blockquote>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button id="show-donation-form" type="button" class="btn btn-default btn-lg btn-block">I'm Ready To Donate</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- actual donation form -->
                                <form id="donation-form" class="donation-step" role="form" data-method="ajax" action="<?php echo MISSGREEK_TOOLS_URL; ?>donation_handler">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Donate On Behalf Of</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" data-field="contestantID">
                                                <?php echo $formHelper->select('donate[contestantID]', Loader::helper('list_transforms', 'miss_greek')->contestantSelectList($contestantList), '', array('class' => 'form-control')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Cardholder Information <span class="text-muted">(Private And <i>NEVER</i> Shared)</span></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group" data-field="firstName">
                                                <label class="sr-only">First Name</label>
                                                <?php echo $formHelper->text('donate[firstName]', '', array('class' => 'form-control', 'placeholder' => 'First Name')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group" data-field="lastName">
                                                <label class="sr-only">Last Name</label>
                                                <?php echo $formHelper->text('donate[lastName]', '', array('class' => 'form-control', 'placeholder' => 'Last Name')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" data-field="email">
                                                <label class="sr-only">Email Address</label>
                                                <?php echo $formHelper->text('donate[email]', '', array('class' => 'form-control tipify', 'placeholder' => 'Email Address', 'title' => 'For donation receipt')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group" data-field="address1">
                                                <label class="sr-only">Billing Address 1</label>
                                                <?php echo $formHelper->text('donate[address1]', '', array('class' => 'form-control', 'placeholder' => 'Address 1')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group" data-field="address2">
                                                <label class="sr-only">Address 2 <span class="text-muted">Optional</span></label>
                                                <?php echo $formHelper->text('donate[address2]', '', array('class' => 'form-control', 'placeholder' => 'Address 2')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group" data-field="addressCity">
                                                <label class="sr-only">City</label>
                                                <?php echo $formHelper->text('donate[addressCity]', '', array('class' => 'form-control', 'placeholder' => 'City')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group" data-field="addressState">
                                                <label class="sr-only">State</label>
                                                <?php echo $formHelper->select('donate[addressState]', array('' => 'State') + $listHelper->getStates(), '', array('class' => 'form-control')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group" data-field="addressZip">
                                                <label class="sr-only">Zip</label>
                                                <?php echo $formHelper->text('donate[addressZip]', '', array('class' => 'form-control', 'placeholder' => 'Zip')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Credit Card Details</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group" data-field="ccNumber">
                                                <label class="sr-only">Credit Card #</label>
                                                <?php echo $formHelper->text('donate[ccNumber]', '', array('class' => 'form-control tipify', 'placeholder' => '#', 'title' => 'All numeric, no spaces')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group" data-field="ccType">
                                                <label class="sr-only">Type</label>
                                                <?php echo $formHelper->select('donate[ccType]', array('' => 'Type', 'visa' => 'Visa', 'mastercard' => 'Mastercard', 'discover' => 'Discover', 'amex' => 'Amex'), '', array('class' => 'form-control')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group" data-field="ccExpMo">
                                                <label class="sr-only">Expiration Month</label>
                                                <?php echo $formHelper->select('donate[ccExpMo]', array('' => 'Month') + array_combine(range(1,12), range(1,12)), '', array('class' => 'form-control')); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group" data-field="ccExpYr">
                                                <label class="sr-only">Expiration Year</label>
                                                <?php echo $formHelper->select('donate[ccExpYr]', array('' => 'Year') + array_combine(range(date(Y),date(Y) + 8), range(date(Y),date(Y) + 8)), '', array('class' => 'form-control')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Donation Amount <span class="text-muted">(Whole Dollar Amount Only)</span></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group" data-field="amount">
                                                <div class="input-group">
                                                    <span class="input-group-addon">$</span>
                                                    <?php echo $formHelper->text('donate[amount]', '', array('class' => 'input-field-amount form-control input-lg')); ?>
                                                    <span class="input-group-addon">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="well well-sm eligibility">Your eligible tax deduction: $<span id="taxDeductionCalc">0</span></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Ticketing <span class="text-muted">(Do You Plan On Attending?)</span></h4>
                                        </div>
                                    </div>
                                    <div id="ticketQualification" data-price="<?php echo (int) $ticketPrice; ?>">
                                        <div id="minimum-met">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p>If you&#39;re planning to attend the event, select "Yes" below to receive a ticket after submitting your donation (immediately by email). By doing so, the ticket price of <strong>$<?php echo $ticketPrice; ?></strong> will <span class="underline">not be eligible for tax deduction</span>, but any amount over $<?php echo $ticketPrice; ?> will be. Your eligible deduction amount is automatically calculated above.</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group" data-field="issueTicket">
                                                        <label class="sr-only">Generate Ticket?</label>
                                                        <?php echo $formHelper->select('donate[issueTicket]', array(MissGreekDonation::ISSUE_TICKET_YES => 'Yes', MissGreekDonation::ISSUE_TICKET_NO => 'No'), MissGreekDonation::ISSUE_TICKET_NO, array('class' => 'purchase-ticket-option form-control')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="minimum-unmet">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p>Your donation does not meet the minimum for a ticket to the event.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h4>Show Name On Supporters List?</h4>
                                            <p>For making a donation, your name will show up on the list of supporters. If you don't want your real name displayed, or (for example) if you want to show your business name, or have your donation appear on someone else's behalf, enter a custom name below.</p>
                                        </div>
                                    </div>
                                    <div id="nameDisplayMethod" class="row">
                                        <div class="col-sm-12">
                                            <div class="form-inline">
                                                <div class="form-group">
                                                    <label class="radio-inline">
                                                        <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CARDHOLDER, 1); ?> Use Cardholder Name
                                                    </label>
                                                    <label class="radio-inline">
                                                        <?php echo $formHelper->radio('donate[nameDisplayMethod]', MissGreekDonation::NAME_DISPLAY_METHOD_CUSTOM, 1, array('data-custom' => 'true')); ?> Custom Name
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="sr-only">Custom Name</label>
                                                    <?php echo $formHelper->text('donate[customName]', '', array('class' => 'form-control', 'placeholder' => 'ie. Ralphie', 'disabled' => 'disabled')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>By clicking "submit donation" below, your card will be charged and you will receive an email receipt shortly. A link to your ticket will be included (if applicable).</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-block btn-lg btn-success">Submit Donation!</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- donation success response -->
                                <div id="donation-success" class="donation-step">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 class="lead">Thanks for supporting CU Greek Gods!</h3>
                                            <div class="resp-msg">
                                                <!-- ajax target -->
                                            </div>
                                            <div class="well resp-ticket">
                                                <!-- if ticket included, href gets updated appropriately -->
                                                <p><strong><a href="#" target="_blank">Click Here For Your Ticket</a></strong></p>
                                                <p><strong>Note:</strong> You can bookmark your ticket to open it later, or take a screen shot if you're on a mobile device. Your email receipt also contains a link to the ticket.</p>
                                            </div>
                                            <p class="text-success">You will receive two emails: one from Authorize.net (our secure transaction provider), and another on behalf of CU Greek Gods and Clinica.</p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="contestantDetails" class="loading">
            <a class="closer"></a>
            <div class="details-inner">
                <div class="inner-L2">
                    <!-- appended via ajax -->
                </div>
            </div>
        </div>
    </div>

<?php Loader::element('footer_required'); // REQUIRED BY C5 // ?>
</body>
</html>