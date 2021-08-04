<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "head.php"; ?>
    </head>
    <body class="">
        <?php include "navbar.php"; ?>
        <?php include 'db_userdata_connect.php'; ?>
        <div class="main-container">
            <section class=" bg--secondary">
                <div class="container">
                    <div class="row justify-content-center no-gutters">
                        <div class="col-md-10 col-lg-8">
                            <div class="text-center">
                                <h2>Create a category</h2>
                            </div>
                            <div class="boxed boxed--border">
                                <?php if ($_SESSION['signed_in'] == false || $_SESSION['user_level'] < 2) { ?>
                                    <p class="lead">Sorry, you do not have sufficient rights to access this page.</p>
                                <?php } else { ?>
                                    <?php if ($_SERVER['REQUEST_METHOD'] != 'POST') { ?>
                                        <form method="post" action="" class="text-left row mx-0">
                                            <div class="col-md-6">
                                                <span>Category name:</span>
                                                <input type="text" name="cat_name" />
                                            </div>
                                            <div class="col-md-12">
                                                <span>Category description:</span>
                                                <textarea rows="5" id="cat_description" name="cat_description"></textarea>
                                            </div>
                                            <div class="col-md-12 boxed">
                                                <button type="submit" class="btn btn--primary type--uppercase">Add category</button>
                                            </div>
                                        </form>
                                    <?php } else { 
                                        $sql = "INSERT INTO categories(cat_name, cat_description) VALUES('" . addslashes($_POST['cat_name']) . "','" . addslashes($_POST['cat_description']) . "')";
                                        $result = $usersqli->query($sql);
                                    ?>
                                        <?php if (!$result) { ?>
                                            <p class="lead">Error <?php echo $usersqli->error; ?></p>
                                        <?php } else { ?>
                                            <p class="lead">New category succesfully added.</p>
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