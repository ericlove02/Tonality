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
                    <div class="row justify-content-left">
                        <div class="col-md-12 col-lg-12">
                            <?php include 'db_userdata_connect.php'; 
                                $sql = "SELECT cat_id,cat_name,cat_description FROM categories WHERE cat_id = " . $_GET['id'];
                                $result = $usersqli->query($sql);  
                            ?>
                            <?php if (!$result) { ?>
                                <p class="lead">The category could not be displayed, please try again later.</p>
                            <?php } else { ?>
                                <?php if ($result->num_rows == 0) { ?>
                                    <p class="lead">This category does not exist.</p>
                                <?php } else { ?>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <h2>Topics in &prime;<?php echo $row['cat_name'] ?>&prime; category</h2>
                                    <?php } ?>
                                    <a href="community"><img src="images/back-arrow.png" class="backarrow"></a>
                                    <?php 
                                        $sql = "SELECT  
                                                topics.topic_id,
                                                topics.topic_subject,
                                                topics.topic_date,
                                                topics.topic_cat,
                                                topics.topic_by
                                            FROM
                                                topics
                                            WHERE
                                                topics.topic_cat = " . $_GET['id'] . " 
                                            ORDER BY
                                                topics.topic_date DESC";
                                        $result = $usersqli->query($sql);
                                    ?>
                                    <?php if (!$result) { ?>
                                        <p class="lead">The topics could not be displayed, please try again later.</p>
                                    <?php } else { ?>
                                        <?php if ($result->num_rows == 0) { ?>
                                            <p class="lead">There are no topics in this category yet.</p>
                                        <?php } else { ?>
                                            <?php while ($row = $result->fetch_assoc()) { 
                                                $preview = $usersqli->query("SELECT post_content FROM posts WHERE post_topic = " . $row['topic_id'] . " ORDER BY post_date ASC")->fetch_assoc() ['post_content'];
                                            ?>
                                            <div class="row mg-18 clickable-row" data-href="topic?id=<?php echo $row['topic_id']; ?>">
                                                <div class="col-lg-8">
                                                    <div class="boxed boxed--lg boxed--border">
                                                        <div class="row">
                                                            <div class="col-md-6 col-lg-6">
                                                                <div class="comments">
                                                                    <h4 class="mgb-5"><?php echo $row['topic_subject']; ?></h4>
                                                                    <p class="ch3"><?php echo date('n/j/y g:ia', strtotime($row['topic_date'])) ?></p>
                                                                    <p><?php echo $preview; ?></p>
                                                                    
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="col-md-6 col-lg-6">
                                                                <div class="text-right">
                                                                    <img src='images/back-arrow.png' class='arrowdeco'></a>
                                                                </div>
                                                            </div>
                                                            <?php if (isset($_SESSION['userID'])) { 
                                                                $user_level = $usersqli->query("SELECT user_level FROM data WHERE userID = " . $_SESSION['userID'])->fetch_assoc() ['user_level'];
                                                                if ($_SESSION['userID'] == $row['topic_by'] || $user_level >= 2) {
                                                            ?>
                                                            <div class="col-md-12 col-lg-12 text-right">
                                                                <a href='delete_topic.php?topic=<?php echo $row['topic_id']; ?>&category=<?php echo $row['topic_cat'] ?>'><svg xmlns="http://www.w3.org/2000/svg" style="color:red;width:36px;height:36px;" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
																  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
																</svg></a>
                                                            </div>
                                                            <?php } }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
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