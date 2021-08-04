<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section class="height-50">
                <div class="container pos-vertical-center">
                    <div class="row">
                        <div class="col-md-8 col-lg-6">
                            <h3>Thank you for contacting the Tonality team!</h3>
                            <p class="lead custom-p">
                                The Tonality website is currently run by a very small team, so we apologize for any mistakes or errors. Here are a few of the things we would like to hear about:
                            </p>
                        </div>
                        <div class="col-md-4 col-lg-6">
                            <ul class="custom-ul">
                                <li>
                                    <strong>Misinformation:</strong> 
                                    At any stage, we may end up with songs with incorrect artists, cover art, or any other information
                                </li>
                                <li>
                                    <strong>Feedback:</strong>
                                    We would love to hear from our users about any aspect of the website to make it better for you!
                                </li>
                                <li>
                                    <strong>Any Errors:</strong> 
                                    Broken links, poorly displayed information, unusable user interface, etc. Let us know and we can fix it!
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
            <section class=" bg--secondary">
                <div class="container">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-10 col-lg-8">
                            <div class="boxed boxed--border">
                                <form action="do_contact.php" method="post" class="text-left row mx-0">
                                    <div class="col-md-6">
                                        <span>First Name:</span>
                                        <input type="text" id="fname" name="firstname" />
                                    </div>
                                    <div class="col-md-6">
                                        <span>Last Name:</span>
                                        <input type="text" id="lname" name="lastname" />
                                    </div>
                                    <div class="col-md-6">
                                        <span>E-Mail:</span>
                                        <input type="text" id="email" name="email" />
                                    </div>
                                    <div class="col-md-6">
                                        <span>Reason for contact:</span>
                                        <select name="reason" id="reason">
                                            <option value="misinfo">Report Misinformation</option>
                                            <option value="feedback">General Suggestion/Feedback</option>
                                            <option value="error">Report Other Error</option>
                                            <option value="addition">Request Music Addition</option>
                                            <option value="removeData">Remove my Data</option>
                                            <option value="business">Business Contact</option>
											<option value="verify">Request Verification</option>
                                            <option value="other">Other</option>
                                          </select>
                                    </div>
                                    <div class="col-md-12">
                                        <span>Message:</span>
                                        <textarea rows="5" id="message" name="message"></textarea>
                                    </div>
                                    
                                    <div class="col-12 col-md-8 boxed">
                                        <div class="g-recaptcha" data-sitekey="6Led0ncaAAAAANY7GzneClTWt2aCVoOUvXOsAraO"></div>
                                    </div>
                                    <div class="col-md-12 boxed">
                                        <button type="submit" class="btn btn--primary type--uppercase">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>