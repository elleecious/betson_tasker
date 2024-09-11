<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BetsonTasker</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/mdb.min.css">
    <link rel="stylesheet" href="css/addons/datatables.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        .poppins-light {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
        }
    </style>
</head>
<body class="poppins-light">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card rounded-0">
                    <div class="card-header" style="background-color: #002E5D;">
                        <h3 class="text-center white-text mb-0">Create Account</h3>
                      </div>
                    <div class="card-body">
                       <form action="">
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
                                        <input class="form-control" type="text">
                                        <label for="position">Position</label>
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
                            <button type="submit" class="btn btn-success justify-content-center">Create Account</button>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/mdb.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>