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
                        <h5 class="mb-0">Edit User Profile</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="frmEditProfile">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="edit_id" id="edit_id" value="<?php echo $user_profile[0]['id']; ?>" hidden>
                                    <div class="md-form">
                                        <input class="form-control" type="text" name="edit_lastname" id="edit_lastname" value="<?php echo $user_profile[0]['lastname']; ?>">
                                        <label for="edit_lastname">Last Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="md-form">
                                        <input class="form-control" type="text" name="edit_firstname" id="edit_firstname" value="<?php echo $user_profile[0]['firstname']; ?>">
                                        <label for="edit_firstname">First Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="md-form">
                                        <input class="form-control" type="text" name="edit_position" id="edit_position" value="<?php echo $user_profile[0]['position']; ?>">
                                        <label for="edit_position">Position</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="md-form">
                                        <input class="form-control" type="text" name="edit_username" id="edit_username" value="<?php echo $user_profile[0]['username']; ?>"> 
                                        <label for="edit_position">Username</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success ml-auto" id="save_profile" name="save_profile">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('includes/footer.php'); ?>