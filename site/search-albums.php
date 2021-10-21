<?php
    require_once "rating_system.php";
    include "db_songs_connect.php";
    include "api_connect.php";
    function exceptions_error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() == 0) {
            return;
        }
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
    }

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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="boxed boxed--border">
                                <form class="text-left row mx-0" name="search" id="search" action="">
                                    <div class="col-md-4">
                                        <span>Search:</span>
                                        <input type="text" name="keyword" value="<?php echo $_GET["keyword"] ?>" onchange="searchChanged(this)" />
                                    </div>
                                    <div class="col-md-4">
                                        <span>Category:</span>
                                        <select name="search-options" id="dropdown" onchange="changeAction(this)">
                                            <option value="search?keyword=">Search All</option>
                                            <option value="search-users?keyword=">Users</option>
                                            <option value="search-albums?keyword=" selected>Albums</option>
                                            <option value="search-songs?keyword=">Song Names</option>
                                            <option value="search-artists?keyword=">Artists</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4 boxed">
                                        <button type="submit" class="btn btn--primary type--uppercase">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="top-text text-left">
                        <?php 
                            if (isset($_GET["keyword"]) && $_GET["keyword"] != "") {
                                $keyword_from_search = $_GET["keyword"];
                                $results = $api->search($keyword_from_search, ['album'], ['limit' => 50]);
                            ?>
                        <?php } else { ?>
                            <h3>Use the search bar to find albums</h3>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table>
                                <tbody>
                                    <?php  
                                        foreach ($results->albums->items as $album) {
                                            $albumName = $album->name;
                                            $spotifyID = $album->id;
                                            try {
                                                $image = $album->images[0]->url;
                                            }
                                            catch(Exception $e) {
                                                $image = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvpxryWjRS0D-c242NDxzJjlqAJ6lldOjdjg&usqp=CAU";
                                            }
                                            $artist = $album->artists[0]->name;
											$artistID = $album->artists[0]->id;

                                    ?>
                                    <tr>
                                        <td class="clickable-row" data-href="album?id=<?php echo $spotifyID; ?>">
                                            <img src="<?php echo $image; ?>" class="img-responsive" height="50"  />
                                        </td>
                                        <td class="font-20 clickable-row" data-href="album?id=<?php echo $spotifyID; ?>"><?php echo $albumName; ?></td>
                                        <td class="font-20 clickable-row" data-href="artist?id=<?php echo $artistID; ?>"><?php echo $artist; ?></td>
                                        <td>
                                            <a href="https://open.spotify.com/track/<?php echo $spotifyID; ?>"><img src="images/spotify.svg" class="img-responsive" height="30"  /></a>
                                        </td>
                                    </tr>
                                    <?php } 
            
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </section>
            <iframe style="display:none" name="target"></iframe>
        </div>
        <?php
            include "footer.php";
            include "script.php";
        ?>
    </body>
</html>