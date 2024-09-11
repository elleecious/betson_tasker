<?php include('includes/header.php'); ?>
    <nav class="mb-1 navbar navbar-expand-lg navbar-dark" style="background-color: #002E5D;">
        <a class="navbar-brand" href="#">
           BetsonTasker
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
        aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
        </button>
        <div class="collapse navbar-collapse" id="basicExampleNav">
            <ul class="navbar-nav ml-auto">
                <a class="nav-link"><span class="fa fa-bell text-white"></span></a>
                <a class="nav-link" id="nightMode"><span class="fa fa-moon text-white"></span></a>
                <a class="nav-link text-white" id="btnLogout">Logout</a>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Welcome, <span id="displayUsername"></span></h2>
        <div class="jumbotron">
            <h4 class="display-4 d-flex justify-content-center" id="status"></h4>
            <h4 class="d-flex justify-content-center font-weight-bold" id="timer"></h4>
            <p class="d-flex justify-content-center" id="breakCount">Breaks Taken: 0</p>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateTask">
            CREATE TASK
        </button>
        <button class="btn btn-success" id="breakButton">Take a Break</button>
        <table class="table table-striped tabled-bordered table-sm text-center" id="tblTasks" width="100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Tasks</th>
                    <th>Position</th>
                    <th>Tasks</th>
                    <th>Assigned By</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="displayTasks"></tbody>
        </table>
    </div>


  <div class="modal fade" id="modalCreateTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Task</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form">
                            <input class="form-control form-control-md" type="text" name="task_title" id="t6ask_title">
                            <label class="text-dark" id="task_title">Title</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="md-form">
                            <input class="form-control form-control-md" type="text" name="task_desc" id="task_desc">
                            <label class="text-dark" for="task_desc">Description</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto" name="add_task" id="add_task">Add</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/mdb.min.js"></script>
    <script src="js/addons/datatables.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
