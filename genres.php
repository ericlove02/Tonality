<?php
include "api_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body>
    <?php include "navbar.php"; ?>
    <section>
        <div class="container">
            <div class="row">
            <?php
                $results = $api->getGenreSeeds();
                foreach($results->genres as $genre) {
            ?>
                <div class="col-md-3 mb--1">
                    <div class="modal-instance block">
                        <a class="btn" href="genre?keyword=<?php echo $genre; ?>">
                            <span class="btn__text">
                                <?php echo ucfirst($genre); ?>
                            </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>
    <?php 
        include "footer.php";
        include "script.php";
    ?>
    </body>
</html>