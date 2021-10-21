<?php
    include "api_connect.php";
    include "db_songs_connect.php";
    $results = $api->getGenreSeeds();
    $seed_genres = "";
    $genreNum = 0;
    foreach ($results->genres as $genre) {
        if (isset($_GET[$genre]) && $_GET[$genre] == 'on' && $genreNum < 5) {
            $seed_genres = $seed_genres . "," . $genre;
            $genreNum++;
        }
    }
    $seed_genres = substr($seed_genres, 1);
    $results = $api->getRecommendations(['seed_genres' => [$seed_genres], 'min_popularity' => 50, 'limit' => 12]);
    $song_num = 1;
    $artistIDs[] = "";
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
                        <h3>VRS Music Discovery - Page 2</h3>
                        <p class="lead">Select <b>up to 5 artists</b> you're interested in:</p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="discover3.php">
                                <table>
                                    <tbody>
                                        <?php  
                                        foreach ($results->tracks as $track) {
                                            $artistID = $track->artists[0]->id;
                                            $trackID = $track->id;
                                            $artist = $api->getArtist($artistID);
                                            $trackArtist = $track->artists[0]->name;
                                            $cover = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
                                            if(isset($artist->images[0]->url)){
                                                $cover = $artist->images[0]->url;
                                            }
                                            $genre = "Unknown genre";
                                            if(isset($artist->genres[0])){
                                                $genre = $artist->genres[0];
                                            }
                                            if (!(in_array($artistID, $artistIDs))) {
                                                $artistIDs[] = $artistID;
                                        ?>
                                       
                                        <tr>
                                            <td><?php echo $song_num; ?></td>
                                            <td class="clickable-row" data-href="artist?id=<?php echo $artistID; ?>">
                                                <img src="<?php echo $cover; ?>" class="img-responsive" height="50"  />
                                            </td>
                                            <td class="clickable-row font-20" data-href="artist?id=<?php echo $artistID; ?>" ><?php echo $trackArtist; ?></td>
                                            <td>
                                                <a href="https://open.spotify.com/artist/<?php echo $artistID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                            </td>
                                            <td>
                                                <div class="input-checkbox">
                                                    <input type="checkbox" name="<?php echo $trackID; ?>" id="<?php echo $trackID; ?>">
                                                    <label for="<?php echo $trackID; ?>"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        
                                        <?php $song_num++;  } } ?>
                                    </tbody>
                                </table>
                                <div class="row" style="justify-content: center;">
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn--primary">Next</button>
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