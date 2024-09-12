<?php include('includes/header.php'); ?>
<?php include('includes/session.php') ?>
<?php $page_title="Betson Tasker"; ?>
<?php include('includes/navbar.php'); ?>
    <div class="container mt-5">
        <h2>Welcome, <span><?php echo $name; ?></span></h2>
        <h6><?php echo $position; ?></h6>
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
                    <?php
                        $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date");
                        foreach ($task_head as $task_val) {
                            echo "<th>".$task_val."</th>";
                        }
                    ?>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php
                    $disp_tasks = retrieve("SELECT * FROM task LEFT JOIN users ON task.user_id=users.id",array());
                    for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                        //To be continued hahaahahahaha
                    }
                ?>
            </tbody>
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
                    <div class="col-md-12">
                        <div class="md-form">
                            <input placeholder="Selected date" type="text" id="task_date" class="form-control datepicker">
                            <label for="task_date">Date</label>
                        </div>
                    </div>
                    <div class="col-md-12 d-none">
                        <div class="md-form">
                            <select class="mdb-select md-form" name="assigned_by" id="assigned_by">
                                <option value="">Select Employee</option>
                                <?php
                                    $employees = retrieve("SELECT * FROM users ORDER BY firstname ASC",array());
                                    for ($i=0; $i < COUNT($employees); $i++) { 
                                        echo "<option value='".$employees[$i]['id']."'>".$employees[$i]['firstname']." ".$employees[$i]['lastname']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto" name="add_task" id="add_task">Add</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
<?php include('includes/footer.php'); ?>
<script>
$(document).ready(function(){

    $('.datepicker').pickadate();

    $('.mdb-select').materialSelect();
    $("#tblTasks").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":10,
        "order": [],
    });
});
</script>
