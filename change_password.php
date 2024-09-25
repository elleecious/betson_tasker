<?php include('includes/header.php'); ?>
<?php include('includes/session.php'); ?>
<?php include('includes/navbar.php') ?>
<?php $page_title="Betson Tasker"; ?>
<?php 
    $user_profile = retrieve("SELECT * FROM users WHERE id=?",array($_GET['edit'])); 
?>
<section>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header betson-color text-white py-3">
                        <h5 class="mb-0">Edit Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="frmChangePassword">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="md-form">
                                        <input class="form-control" type="password" name="current_password" id="current_password">
                                        <label for="current_password">Current Password</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="md-form">
                                        <input class="form-control" type="password" name="new_password" id="new_password">
                                        <label for="new_password">New Password</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="md-form">
                                        <input class="form-control" type="password" name="confirm_password" id="confirm_password">
                                        <label for="confirm_password">Confirm Password</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success ml-auto" id="save_password" name="save_password">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('includes/footer.php'); ?>