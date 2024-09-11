<?php include('includes/header.php');  ?>
<?php $page_title="Betson Tasker"; ?>
  <div class="container py-5 mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12 mx-auto">
              <h1 class="text-center">BetsonTasker</h1>
              <h6 class="text-center">Bet on Your Productivity, Win Every Task!</h6>
              <div class="card rounded-0">
                  <div class="card-header" style="background-color: #002E5D;">
                    <h3 class="text-center white-text mb-0">Login</h3>
                  </div>
                <div class="card-body">
                  <form class="form" method="post" role="form" autocomplete="off" id="formLogin">
                    <div class="md-form">
                      <i class="fa fa-user prefix"></i>
                      <input class="form-control form-control-lg rounded-0" type="text" name="username" id="username" required>
                      <label class="text-dark" id="username">Username</label>
                    </div>
                    <div class="md-form">
                      <i class="fa fa-lock prefix"></i>
                      <input class="form-control form-control-lg rounded-0" type="password" name="password" id="password" required>
                      <label class="text-dark" for="password">Password</label>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                      <a class="btn btn-primary btn-lg" href="register.php"><span class="fa fa-user-plus"></span> Create Account</a>
                      <button class="btn btn-dark btn-lg" type="submit" name="btnLogin" id="btnLogin"><i class="fa fa-sign-in"></i> Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php include('includes/footer.php'); ?>