<?php
include ("top.php");

// Get URL
$thisURL = DOMAIN . PHP_SELF;

$orderNum = 0;

$orderNumError = false;

$errorMsg = array();

if (isset($_GET["btnSubmit"]) or isset($_GET["btnCancel"])) {
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // Security
    //
    //    if (securityCheck($thisURL)) {
    //        $msg = '<p class="container">Sorry you cannot access this page. ';
    //        $msg .= 'Security breach detected and reported.</p>';
    //        print($msg);
    //    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // Sanitize (clean) data
    $orderNum = htmlentities($_GET["order"], ENT_QUOTES, "UTF-8");

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2c Validation
    //
    if ($orderNum == "") {
        $errorMsg[] = 'Please enter your order number.';
        $orderNumError = true;
    } elseif (!verifyNumeric($orderNum)) {
        $errorMsg[] = 'Your order number appears to be incorrect.';
        $phoneError = true;
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // Process Form - Passed Validation
    //
    if (empty($errorMsg)) {
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // Save/Delete Data
        //
        // This block saves the data to the global variables in top to pass to next form or deletes it
        if (isset($_GET["btnSubmit"])) {
            header('Location: https://jlaquerr.w3.uvm.edu/cs148/live-final/order.php' . '?updateOrderNum=' . strval($orderNum));
        } else {
            $query = "DELETE FROM `Orders` WHERE `Order_Num` = ?";
            if ($thisDatabaseWriter->querySecurityOk($query, 1, 0, 0, 0, 0)) {
                $query = $thisDatabaseWriter->sanitizeQuery($query, 0, 0, 0, 0, 0);
                $thisDatabaseWriter->delete($query, array(strval($orderNum)));
            }
        }
        if (isset($_GET["btnCancel"])) {
            //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            //
            // Create message
            //

//            $message = '<h2>Order Cancelled</h2>';
//            $message .= "<p>You order (#" . strval($orderNum) . ") Has been cancelled.</p>";
//            $message .= "<p>You will be sent an email confirmation.</p>";
            //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
            //
            // Mail to user
            //

            //            $to = $email; // the person who filled out the form
            //            $cc = 'mzahar@uvm.edu';
            //            $bcc = '';
            //
            //            $from = 'mzahar@uvm.edu';
            //
            //            // subject of main should make sense to your form
            //            $subject = 'Your sandwiches order: ';
            //
            //            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
        }
    }
}
?>

<article id="main" class="container">

<?php
if (isset($_GET["btnCancel"]) AND empty($errorMsg)) { //closing if marked with: end body submit
    print '<h2>Order Cancelled</h2>';
    print"<p>You order (#" . strval($orderNum) . ") Has been cancelled.</p>";
    print '';
    print "<p>You will be sent an email confirmation.</p>";

} else {
    print '';

    //#########################################################################
    //
    // Error Messages
    //
    if ($errorMsg) {
    print '<section id="errors">' . PHP_EOL;
        print '<h3>Your form has the following mistakes that need to be fixed.</h3>' . PHP_EOL;
        print '<ol>' . PHP_EOL;

            foreach ($errorMsg as $err) {
            print '<li>' . $err . '</li>' . PHP_EOL;
            }
            print '</ol>' . PHP_EOL;
        print '</section>' . PHP_EOL;
    }
    ?>
    <section>
        <p>If you wish to change your order after you have submitted it, you can do so by looking it up below.</p>
        <form action = "<?php print PHP_SELF; ?>"
              id="frmOption"
              method = "get"
              class="form_container">
            <fieldset class="contact row">
                <section class="col-25">
                    <legend class="legend">Search by Order Number</legend>
                </section>
                <section class="col-75">
                    <label for="order">Order #</label>
                    <input type="text" id="order" name="order">
                </section>
            </fieldset>

            <!-- Start Submit button -->
            <fieldset class="buttons row">
                <section class="col-25">
                    <legend class="legend">Lookup Order</legend>
                </section>
                <section class="col-75">
                    <input
                            class="button"
                            id="btnSubmit"
                            name="btnSubmit"
                            tabindex="1500"
                            type="submit"
                            value="Update">
                </section>
                <section class="col-75">
                    <input
                            class="button"
                            id="btnCancel"
                            name="btnCancel"
                            tabindex="1500"
                            type="submit"
                            value="Cancel">
                </section>
            </fieldset>
            <!-- ends submit button -->
        </form>
    </section>
    <?php
    } //end body submit
    ?>
</article>
<?php
include ("footer.php");
?>
