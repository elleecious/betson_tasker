<?php include('includes/header.php') ?>
<?php $page_title="Betson Tasker"; ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card rounded-0">
                <div class="card-header" style="background-color: #002E5D;">
                    <h3 class="text-center white-text mb-0">Create Account</h3>
                    </div>
                <div class="card-body">
                    <form id="frmRegistration" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="md-form">
                                    <span class="fa fa-user-circle prefix"></span>
                                    <input class="form-control" type="text" name="lastname" id="lastname">
                                    <label for="lastname">Last Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="md-form">
                                    <span class="fa fa-user-circle prefix"></span>
                                    <input class="form-control" type="text" name="firstname" id="firstname">
                                    <label for="firstname">First Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="md-form">
                                    <span class="fa fa-building prefix"></span>
                                    <input class="form-control" type="text" name="position" id="position">
                                    <label for="position">Position</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="md-form">
                                    <select class="mdb-select md-form" name="level" id="level">
                                        <option value="">Select Tean</option>
                                        <option value="1">Executive</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Leadership</option>
                                        <option value="4">Agent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="md-form">
                                    <span class="fa fa-user-circle prefix"></span>
                                    <input class="form-control" type="text" name="username" id="username">
                                    <label for="username">Username</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-form">
                                    <span class="fa fa-lock prefix"></span>
                                    <input class="form-control" type="password" name="password" id="password">
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="md-form">
                                    <span class="fa fa-lock prefix"></span>
                                    <input class="form-control" type="password" name="confirm_password" id="confirm_password">
                                    <label for="confirm_password">Confirm Password</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success justify-content-center" id="register" name="register">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<script>
    $(document).ready(function () {
        $('.mdb-select').materialSelect();
    });
</script>