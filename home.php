<?php include('includes/header.php'); ?>
<?php include('includes/session.php') ?>
<?php include('library/functions.php'); ?>
<?php $page_title="Betson Tasker"; ?>
<?php include('includes/navbar.php'); ?>
    <div class="container py-5">
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
        <?php
           echo ($level=="1") ? '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAssignTask">
            ASSIGN A TASK
        </button>' : ''
        ?>
        <button class="btn btn-success" id="breakButton">Take a Break</button>
        <div class="row my-4">
            <div class="col-md-12">
                <section class="mx-2 pb-3">
                    <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="new-tab-md" data-toggle="tab" href="#new-md" role="tab" aria-controls="new-md"
                            aria-selected="true">New Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pending-tab-md" data-toggle="tab" href="#pending-md" role="tab" aria-controls="pending-md"
                            aria-selected="true">Pending Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ongoing-tab-md" data-toggle="tab" href="#ongoing-md" role="tab" aria-controls="ongoing-md"
                            aria-selected="false">Ongoing Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="completed-tab-md" data-toggle="tab" href="#completed-md" role="tab" aria-controls="completed-md"
                            aria-selected="false">Completed Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="onhold-tab-md" data-toggle="tab" href="#onhold-md" role="tab" aria-controls="onhold-md"
                            aria-selected="false">On Hold Tasks</a>
                        </li>
                    </ul>
                    <div class="tab-content card pt-5" id="myTabContentMD">
                        <div class="tab-pane fade show active" id="new-md" role="tabpanel" aria-labelledby="new-tab-md">
                            <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksNew" width="100%" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <?php
                                            $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date Assigned,Due Date,Actions");
                                            foreach ($task_head as $task_val) {
                                                echo "<th>".$task_val."</th>";
                                            }
                                        ?>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $disp_tasks = retrieve("SELECT
                                            CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                users.level AS level,
                                                task.title AS title,
                                                task.id AS task_id,
                                                task.description AS description,
                                                task.assign_by AS assign_by,
                                                task.status AS status,
                                                task.task_date AS task_date,
                                                task.due_date AS due_date 
                                                FROM task LEFT JOIN users ON task.user_id=users.id 
                                                WHERE users.username=?",array($login_username));
                                            for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                                                echo "<tr>
                                                        <td>".$disp_tasks[$i]['task_id']."</td>
                                                        <td>".$disp_tasks[$i]['position']."</td>
                                                        <td>".$disp_tasks[$i]['name']."</td>
                                                        <td>".$disp_tasks[$i]['title']."</td>
                                                        <td>
                                                            <details>
                                                                ".$disp_tasks[$i]['description']."
                                                            </details>
                                                        </td>
                                                        <td>".$disp_tasks[$i]['assign_by']."</td>
                                                        ".getTaskStatus($disp_tasks[$i]['status'])."
                                                        <td>".date("F d, Y",strtotime($disp_tasks[$i]['task_date']))."</td>
                                                        <td>".date("F d, Y", strtotime($disp_tasks[$i]['due_date']))."</td>
                                                        <td>
                                                            <a class='mr-1 move_task'><span class='fas fa-arrow-alt-circle-right'></span></a>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#edit_task_modal'>
                                                                <i class='fas fa-edit'></i>
                                                            </span>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#delete_task_modal'>
                                                                <i class='fas fa-trash'></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pending-md" role="tabpanel" aria-labelledby="pending-tab-md">
                            <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksPending" width="100%" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <?php
                                            $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date Assigned,Due Date,Actions");
                                            foreach ($task_head as $task_val) {
                                                echo "<th>".$task_val."</th>";
                                            }
                                        ?>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $disp_tasks = retrieve("SELECT
                                            CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                users.level AS level,
                                                task.title AS title,
                                                task.id AS task_id,
                                                task.description AS description,
                                                task.assign_by AS assign_by,
                                                task.status AS status,
                                                task.task_date AS task_date,
                                                task.due_date AS due_date 
                                                FROM task LEFT JOIN users ON task.user_id=users.id 
                                                WHERE users.username=?",array($login_username));
                                            for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                                                echo "<tr>
                                                        <td>".$disp_tasks[$i]['task_id']."</td>
                                                        <td>".$disp_tasks[$i]['position']."</td>
                                                        <td>".$disp_tasks[$i]['name']."</td>
                                                        <td>".$disp_tasks[$i]['title']."</td>
                                                        <td>
                                                            <details>
                                                                ".$disp_tasks[$i]['description']."
                                                            </details>
                                                        </td>
                                                        <td>".$disp_tasks[$i]['assign_by']."</td>
                                                        ".getTaskStatus($disp_tasks[$i]['status'])."
                                                        <td>".date("F d, Y",strtotime($disp_tasks[$i]['task_date']))."</td>
                                                        <td>".date("F d, Y", strtotime($disp_tasks[$i]['due_date']))."</td>
                                                        <td>
                                                            <a class='mr-1 move_task'><span class='fas fa-arrow-alt-circle-right'></span></a>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#edit_task_modal'>
                                                                <i class='fas fa-edit'></i>
                                                            </span>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#delete_task_modal'>
                                                                <i class='fas fa-trash'></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                    ?>
                                </tbody>
                            </table> 
                        </div>
                        <div class="tab-pane fade" id="ongoing-md" role="tabpanel" aria-labelledby="ongoing-tab-md">
                            <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksOngoing" width="100%" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <?php
                                            $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date Assigned,Due Date,Actions");
                                            foreach ($task_head as $task_val) {
                                                echo "<th>".$task_val."</th>";
                                            }
                                        ?>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $disp_tasks = retrieve("SELECT
                                            CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                users.level AS level,
                                                task.title AS title,
                                                task.id AS task_id,
                                                task.description AS description,
                                                task.assign_by AS assign_by,
                                                task.status AS status,
                                                task.task_date AS task_date,
                                                task.due_date AS due_date 
                                                FROM task LEFT JOIN users ON task.user_id=users.id 
                                                WHERE users.username=?",array($login_username));
                                            for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                                                echo "<tr>
                                                        <td>".$disp_tasks[$i]['task_id']."</td>
                                                        <td>".$disp_tasks[$i]['position']."</td>
                                                        <td>".$disp_tasks[$i]['name']."</td>
                                                        <td>".$disp_tasks[$i]['title']."</td>
                                                        <td>
                                                            <details>
                                                                ".$disp_tasks[$i]['description']."
                                                            </details>
                                                        </td>
                                                        <td>".$disp_tasks[$i]['assign_by']."</td>
                                                        ".getTaskStatus($disp_tasks[$i]['status'])."
                                                        <td>".date("F d, Y",strtotime($disp_tasks[$i]['task_date']))."</td>
                                                        <td>".date("F d, Y", strtotime($disp_tasks[$i]['due_date']))."</td>
                                                        <td>
                                                            <a class='mr-1 move_task'><span class='fas fa-arrow-alt-circle-right'></span></a>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#edit_task_modal'>
                                                                <i class='fas fa-edit'></i>
                                                            </span>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#delete_task_modal'>
                                                                <i class='fas fa-trash'></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="completed-md" role="tabpanel" aria-labelledby="completed-tab-md">
                            <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksCompleted" width="100%" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <?php
                                            $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date Assigned,Due Date,Actions");
                                            foreach ($task_head as $task_val) {
                                                echo "<th>".$task_val."</th>";
                                            }
                                        ?>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $disp_tasks = retrieve("SELECT
                                            CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                users.level AS level,
                                                task.title AS title,
                                                task.id AS task_id,
                                                task.description AS description,
                                                task.assign_by AS assign_by,
                                                task.status AS status,
                                                task.task_date AS task_date,
                                                task.due_date AS due_date 
                                                FROM task LEFT JOIN users ON task.user_id=users.id 
                                                WHERE users.username=?",array($login_username));
                                            for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                                                echo "<tr>
                                                        <td>".$disp_tasks[$i]['task_id']."</td>
                                                        <td>".$disp_tasks[$i]['position']."</td>
                                                        <td>".$disp_tasks[$i]['name']."</td>
                                                        <td>".$disp_tasks[$i]['title']."</td>
                                                        <td>
                                                            <details>
                                                                ".$disp_tasks[$i]['description']."
                                                            </details>
                                                        </td>
                                                        <td>".$disp_tasks[$i]['assign_by']."</td>
                                                        ".getTaskStatus($disp_tasks[$i]['status'])."
                                                        <td>".date("F d, Y",strtotime($disp_tasks[$i]['task_date']))."</td>
                                                        <td>".date("F d, Y", strtotime($disp_tasks[$i]['due_date']))."</td>
                                                        <td>
                                                            <a class='mr-1 move_task'><span class='fas fa-arrow-alt-circle-right'></span></a>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#edit_task_modal'>
                                                                <i class='fas fa-edit'></i>
                                                            </span>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#delete_task_modal'>
                                                                <i class='fas fa-trash'></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="onhold-md" role="tabpanel" aria-labelledby="onhold-tab-md">
                            <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksOnHold" width="100%" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <?php
                                            $task_head = explode(",","No,Name,Position,Title,Description,Assigned By,Status,Date Assigned,Due Date,Actions");
                                            foreach ($task_head as $task_val) {
                                                echo "<th>".$task_val."</th>";
                                            }
                                        ?>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $disp_tasks = retrieve("SELECT
                                            CONCAT(users.firstname, ' ', users.lastname) AS name,
                                                users.position AS position,
                                                users.level AS level,
                                                task.title AS title,
                                                task.id AS task_id,
                                                task.description AS description,
                                                task.assign_by AS assign_by,
                                                task.status AS status,
                                                task.task_date AS task_date,
                                                task.due_date AS due_date 
                                                FROM task LEFT JOIN users ON task.user_id=users.id 
                                                WHERE users.username=?",array($login_username));
                                            for ($i=0; $i < COUNT($disp_tasks); $i++) { 
                                                echo "<tr>
                                                        <td>".$disp_tasks[$i]['task_id']."</td>
                                                        <td>".$disp_tasks[$i]['position']."</td>
                                                        <td>".$disp_tasks[$i]['name']."</td>
                                                        <td>".$disp_tasks[$i]['title']."</td>
                                                        <td>
                                                            <details>
                                                                ".$disp_tasks[$i]['description']."
                                                            </details>
                                                        </td>
                                                        <td>".$disp_tasks[$i]['assign_by']."</td>
                                                        ".getTaskStatus($disp_tasks[$i]['status'])."
                                                        <td>".date("F d, Y",strtotime($disp_tasks[$i]['task_date']))."</td>
                                                        <td>".date("F d, Y", strtotime($disp_tasks[$i]['due_date']))."</td>
                                                        <td>
                                                            <a class='mr-1 move_task'><span class='fas fa-arrow-alt-circle-right'></span></a>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#edit_task_modal'>
                                                                <i class='fas fa-edit'></i>
                                                            </span>
                                                            <span class='mr-1 edit_task'
                                                                data-toggle='modal' data-target='#delete_task_modal'>
                                                                <i class='fas fa-trash'></i>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
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
            <form method="post" id="frmCreateTask">
                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form">
                            <input class="form-control form-control-md" type="text" name="task_title" id="task_title">
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
                            <input placeholder="Selected date" type="text" name="task_date" id="task_date" class="form-control datepicker">
                            <label for="task_date">Assigned Date</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="md-form">
                            <input placeholder="Selected date" type="text" name="due_date" id="due_date" class="form-control datepicker">
                            <label for="task_date">Due Date</label>
                        </div>
                    </div>
                    <div class="col-md-12 d-none">
                        <div class="md-form">
                            <select class="mdb-select md-form" name="assign_by" id="assign_by">
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
    $("#tblTasksNew").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":10,
        "order": [],
    });
    $("#tblTasksPending").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":10,
        "order": [],
    });
    $("#tblTasksOngoing").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":10,
        "order": [],
    });
    $("#tblTasksCompleted").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":10,
        "order": [],
    });
    $("#tblTasksOnHold").DataTable({
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
