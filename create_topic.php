<?php  
include 'db_userdata_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <div class="main-container">
            <section class=" bg--secondary">
                <div class="container">
                    <div class="text-center">
                        <h3>Create a topic</h3>
                    </div>
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-10 col-lg-8">
                            <div class="boxed boxed--border">
                                <?php 
                                include 'db_userdata_connect.php';
                                if (!(isset($_SESSION['signed_in'])) || $_SESSION['signed_in'] == false) { ?>
                                    <p class="lead">Sorry, you have to be <a href="login">signed in</a> to create a topic.</p>
                                <?php } else { 
                                    if (isset($_GET["setId"])) {
                                        $setId = $_GET["setId"];
                                    }
                                ?>
                                <?php if ($_SERVER['REQUEST_METHOD'] != 'POST') { 
                                    $sql = "SELECT cat_id,cat_name,cat_description FROM categories";
                                    $result = $usersqli->query($sql);
                                ?>
                                    <?php if (!$result) { ?>
                                        <p class="lead">Error while selecting from database. Please try again later.</p>
                                    <?php } else { ?>
                                        <?php if ($result->num_rows == 0) { ?>
                                            <?php if ($_SESSION['user_level'] == 1) { ?>
                                                <p class="lead">You have not created categories yet.</p>
                                            <?php } else { ?>
                                                <p class="lead">Before you can post a topic, you must wait for an admin to create some categories.</p>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <form method="post" action="" class="text-left row ">
                                                <div class="col-md-6">
                                                    <span>Subject:</span>
                                                    <input type="text" name="topic_subject" />
                                                </div>
                                                <div class="col-md-6">
                                                    <span>Category:</span>
                                                    <select name="topic_cat">
                                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                                            <?php if ($row['cat_id'] == $setId) { ?>
                                                                <option value="<?php echo $row['cat_id']; ?>" selected><?php echo $row['cat_name']; ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>    
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <span>Message:</span>
                                                    <textarea rows="5" id="post_content" name="post_content"></textarea>
                                                </div>
                                                <div class="col-md-12 boxed">
                                                    <button type="submit" class="btn btn--primary type--uppercase">Create topic</button>
                                                </div>
                                            </form>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { 
                                    $query = "BEGIN WORK;";
                                    $result = $usersqli->query($query);
                                ?>
                                    <?php if (!$result) { ?>
                                        <p class="lead">An error occured while creating your topic. Please try again later.</p>
                                    <?php } else { 
                                        $sql = "INSERT INTO topics(topic_subject,topic_date,topic_cat,topic_by) VALUES('" . addslashes($_POST['topic_subject']) . "',NOW()," . $_POST['topic_cat'] . "," . $_SESSION['userID'] . ")";
                                        $result = $usersqli->query($sql);
                                    ?>
                                    <?php if (!$result) { 
                                        echo '<p class="lead">An error occured while inserting your data. Please try again later.</p>';
                                        $sql = "ROLLBACK;";
                                        $result = $usersqli->query($sql);
                                    ?>
                                    <?php } else { 
                                        $topicid = mysqli_insert_id($usersqli);
                                        $sql = "INSERT INTO
                                            posts(post_content,
                                                  post_date,
                                                  post_topic,
                                                  post_by)
                                            VALUES
                                            ('" . addslashes($_POST['post_content']) . "',
                                                  NOW(),
                                                  " . $topicid . ",
                                                  " . $_SESSION['userID'] . "
                                            )";
                                        $result = $usersqli->query($sql);
                                    ?>
                                    <?php if (!$result) { 
                                        echo '<p class="lead">An error occured while inserting your post. Please try again later.</p>';
                                        $sql = "ROLLBACK;";
                                        $result = $usersqli->query($sql);
                                    ?>

                                    <?php } else {
                                        $sql = "COMMIT;";
                                        $result = $usersqli->query($sql);
                                        echo '<p class="lead">Topic has been created, view it <a href="topic?id=' . $topicid . '">here</a></p>';
                                    ?>

                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php } ?>
                                
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