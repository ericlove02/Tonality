<?php
    include "api_connect.php";
    include "db_songs_connect.php";
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $results = explode("=on&", parse_url($url) ['query']);
    $seed_tracks = array();
    foreach ($results as $id) {
        array_push($seed_tracks, substr($id, 0, 22));
    }
    $results = $api->getRecommendations(['seed_tracks' => $seed_tracks, 'limit' => 12, 'market'=>'US']);
    $song_num = 1;
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
                        <h3>VRS Music Discovery - Page 3</h3>
                        <p class="lead">Select <b>up to 5 songs</b> you're interested in:</p>
                    </div>
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <form action="discover-results.php">
                                <div class="row">
                                    <?php foreach ($results->tracks as $track) { $spotifyID = $track->id; ?>
                                            
                                            <div class="col-md-4 col-6 text-center">
                                                <div class="input-checkbox">
                                                    <input type="checkbox" name="<?php echo $spotifyID; ?>" id="<?php echo $spotifyID; ?>">
                                                    <label for="<?php echo $spotifyID; ?>"></label>
                                                </div>
                                                <iframe style="min-height: unset;" src="https://open.spotify.com/embed/track/<?php echo $spotifyID; ?>" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                                            </div>
                                        
                                    <?php } ?>

                                </div>
                                <div class="row" style="justify-content: center;">
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn--primary">Submit</button>
                                    </div>
                                    <div class="col-md-4">
                                        
                                        <a onclick="location.reload();" class="btn btn--primary" href="javascript:void(0)">
                                            <span class="btn__text">Don't like any of these?</span>
                                        </a>
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