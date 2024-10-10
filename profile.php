<?php include('includes/header.php'); ?>
<?php include('includes/session.php'); ?>
<?php include('library/functions.php') ?>
<?php $page_title = "Betson Tracker"; ?>
<?php
    $user_profile = retrieve("SELECT * FROM users WHERE id=?",array($_GET['id']));
?>
<?php include('includes/navbar.php'); ?>
<section>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="./img/avatar.avif" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3"><?php echo $user_profile[0]['firstname'] ." ". $user_profile[0]['lastname']; ?></h5>
                        <p class="text-muted mb-1"><?php echo $user_profile[0]['position'] ?></p>
                        <p class="text-muted mb-4">Bay Area, San Francisco, CA</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" onclick="window.location.href='edit_profile.php?edit=<?php echo $user_profile[0]['id']; ?>'">Edit Profile</button>
                            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-secondary" onclick="window.location.href='change_password.php?edit=<?php echo $user_profile[0]['id']; ?>'">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $user_profile[0]['firstname']." ". $user_profile[0]['lastname']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Position</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $user_profile[0]['position']; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Team</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php getLevel($user_profile[0]['level']); ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Username</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo $user_profile[0]['username']; ?></p>
                            </div>
                        </div>
                        <div class="row d-none">
                            <div class="col-sm-3">
                                <p class="mb-0">Mobile</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">(098) 765-4321</p>
                            </div>
                        </div>
                        <!-- <hr> -->
                        <div class="row d-none">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include('includes/footer.php'); ?>