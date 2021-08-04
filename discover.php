<?php
    include "api_connect.php";
    $results = $api->getGenreSeeds();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="top-text text-left">
                        <h3>Welcome the Tonality Music Discovery</h3>
                        <p class="lead">Here you can take a ~5 minute quiz that will get a sense of your music taste, and recommend you a few songs! Each time you take the quiz you will get different results, so keep trying until you find something you like. Enjoy!</p>
                        <p class="lead">Select <b>up to 5 genres</b> you're interested in:</p>
                    </div>
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <form action="discover2.php">
                                <div class="row">
                                    <?php foreach ($results->genres as $genre) { ?>
                                        
                                            <div class="col-md-3 col-6 text-center">
                                                <span class="block"><?php echo ucfirst($genre); ?></span>
                                                <div class="input-checkbox">
                                                    <input type="checkbox" name="<?php echo $genre; ?>" id="<?php echo $genre; ?>">
                                                    <label for="<?php echo $genre; ?>"></label>
                                                </div>
                                            </div>
                                        
                                    <?php } ?>

                                </div>
                                <div class="row" style="justify-content: center;">
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn--primary">Next</button>
                                    </div>
                                </div>
                            </form>
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