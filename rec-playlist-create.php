<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php  
            require 'vendor/autoload.php';
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($_SESSION['token']);
            $playlist = $api->createPlaylist(['name' => "My VRS Recommendations", 'public' => False]);
            $playlistID = $playlist->id;
            if (!(isset($_SESSION))) {
                session_start();
            }
            $recommendations = $_SESSION['recommendations'];
            $api->addPlaylistTracks($playlistID, $recommendations);
            $api->updatePlaylist($playlistID, ['description' => "Playlist created with gotonality.com/discover that helps you find new music!"]);
        ?>
        <div class="main-container">
            <section>
                <div class="container">
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <h2>VRS Music Discovery - Exported</h2>
                            <p class="lead">Your playlist has been added to Spotify. Enjoy!</p>
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