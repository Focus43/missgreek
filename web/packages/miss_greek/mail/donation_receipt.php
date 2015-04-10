<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var $donationObj MissGreekDonation */
/** @var $receiptHelper ReceiptLanguageHelper */
$receiptHelper = Loader::helper('receipt_language', 'miss_greek');
$receiptHelper->setDonationObject($donationObj);
$messageText   = $receiptHelper->thankYouText();

$ticketAccess  = '';
if( $donationObj->getIssueTicketStatus() === MissGreekDonation::ISSUE_TICKET_YES ){
    $ticketAccess = '<p style="text-align:center;padding:20px;"><a href="'.$donationObj->getTicketObj()->getTicketLinkURL().'" target="_blank">Click Here To Access Your Ticket</a></p>';
}

$template = <<< heredoc
<html>
    <head>
        <title>Clinica.org Donation Receipt (CU Greek Gods)</title>
        <style type="text/css">
            body {margin:0;padding:0;font-family:Arial;font-size:13px;font-weight:normal;line-height:120%;}
			body {-webkit-text-size-adjust:none;}
			table td {border-collapse:collapse;}
			p {font-size:13px;line-height:130%;}
        </style>
    </head>
    <body style="background-color:#f5f5f5;" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<center>
			<br /><br />
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="height:100% !important;margin:0;padding:0;width:100% !important;">
				<tr>
					<td valign="top">
						<center>
							<h1 class="h1">CU Greek Gods Donation Receipt</h1>
							<table cellpadding="0" cellspacing="0" width="600" style="background-color:#fff;border:1px solid #e5e5e5;">
								<tr>
									<td valign="top">
										<table border="0" cellpadding="10" cellspacing="0" width="600">
											<tr>
												<td>
												    {$messageText}
												    {$ticketAccess}
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
			<br /><br />
		</center>
	</body>
</html>
heredoc;

$bodyHTML = $template;