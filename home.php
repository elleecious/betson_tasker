<?php include('includes/header.php'); ?>
<?php include('includes/session.php') ?>
<?php include('library/functions.php'); ?>
<?php 
    $page_title="Betson Tasker"; 
    if (!isset($_SESSION['login_id'])) {
        header('Location: index.php');
    }

?>
<?php include('includes/navbar.php'); ?>
<div class="container py-5">
    <h2>Welcome, <span><?php echo $name; ?></span></h2>
    <h6><?php echo $position; ?></h6>
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
                <h4 class="display-4 d-flex justify-content-center" id="status"></h4>
                <h4 class="d-flex justify-content-center font-weight-bold" id="timer">00:00</h4>
                <!-- <p class="d-flex justify-content-center d-none" id="breakCount">Breaks Taken: 0</p> -->
            </div>
        </div>
    </div>
    <hr class="divider">
    <button type="button" class="btn blue-gradient btn-rounded" data-toggle="modal" data-target="#modalCreateTask">
        CREATE TASK
    </button>
    <?php
        echo ($level=="1") ? '<button type="button" class="btn btn-info btn-rounded" data-toggle="modal" data-target="#modalAssignTask">
        ASSIGN A TASK
    </button>' : '';
    ?>
    <button class="btn btn-rounded text-white" style="background-color: #43cea2;" id="breakButton">Take a Break</button>
    <button class="btn btn-rounded text-white" style="background-color: #FF5733;" id="endBreakButton">End Break</button>
    <button class="btn btn-rounded text-white btn-warning d-none" id="lunchBreakButton">Lunch Break</button>

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
                        aria-selected="false">Paused Tasks</a>
                    </li>
                </ul>
                <div class="tab-content card pt-5" id="myTabContentMD">
                    <div class="tab-pane fade show active" id="new-md" role="tabpanel" aria-labelledby="new-tab-md">
                        <table class="table table-hoverable tabled-bordered table-sm text-center" id="tblTasksNew" style="width: 100%;" cellspacing="0" cellpadding="0">
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
                                    $disp_task_new = retrieve("SELECT
                                        CONCAT(users.firstname, ' ', users.lastname) AS name,
                                            users.position AS position,
                                            users.level AS level,
                                            task.title AS title, 
                                            task.id AS task_id, 
                                            task.description AS description,
                                            task.assign_by AS assign_by,
                                            task.task_status AS task_status,
                                            task.task_date AS task_date,
                                            task.due_date AS due_date 
                                            FROM task LEFT JOIN users ON task.user_id=users.id 
                                            WHERE users.id=? AND task.task_status=?",array($login_id,"1"));
                                        for ($i=0; $i < COUNT($disp_task_new); $i++) { 
                                            echo "<tr>
                                                    <td>".$disp_task_new[$i]['task_id']."</td>
                                                    <td>".$disp_task_new[$i]['name']."</td>
                                                    <td>".$disp_task_new[$i]['position']."</td>
                                                    <td>".$disp_task_new[$i]['title']."</td>
                                                    <td>
                                                        <details>
                                                            ".$disp_task_new[$i]['description']."
                                                        </details>
                                                    </td>
                                                    <td>".$disp_task_new[$i]['assign_by']."</td>
                                                    ".getTaskStatus($disp_task_new[$i]['task_status'])."
                                                    <td>".date("F d, Y",strtotime($disp_task_new[$i]['task_date']))."</td>
                                                    <td>".date("F d, Y", strtotime($disp_task_new[$i]['due_date']))."</td>
                                                    <td>
                                                        <a class='mr-1 move_to_pending_task' 
                                                            data-task-id=".$disp_task_new[$i]['task_id']."
                                                            data-current-status=".$disp_task_new[$i]['task_status'].">
                                                            <span class='fas fa-arrow-alt-circle-right'></span>
                                                        </a>
                                                        <a class='mr-1 paused_task'
                                                            data-task-id=".$disp_task_new[$i]['task_id']."
                                                            data-current-status=".$disp_task_new[$i]['task_status']."
                                                            >
                                                            <span class='fas fa-pause-circle'></span>
                                                        </a>
                                                        <span class='mr-1 edit_task'
                                                            edit_task_id='".$disp_task_new[$i]['task_id']."'
                                                            edit_task_title='".$disp_task_new[$i]['title']."'
                                                            edit_task_desc='".$disp_task_new[$i]['description']."'
                                                            edit_task_date='".$disp_task_new[$i]['task_date']."'
                                                            edit_task_due='".$disp_task_new[$i]['due_date']."'
                                                            data-toggle='modal' data-target='#edit_task_modal'>
                                                            <i class='fas fa-edit'></i>
                                                        </span>
                                                        <span class='mr-1 delete_task'
                                                            data-task-id='".$disp_task_new[$i]['task_id']."'>
                                                            <i class='fa fa-trash'></i>
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
                                    $disp_tasks_pending = retrieve("SELECT
                                        CONCAT(users.firstname, ' ', users.lastname) AS name,
                                            users.position AS position,
                                            users.level AS level,
                                            task.title AS title,
                                            task.id AS task_id,
                                            task.description AS description,
                                            task.assign_by AS assign_by,
                                            task.task_status AS task_status,
                                            task.task_date AS task_date,
                                            task.due_date AS due_date 
                                            FROM task LEFT JOIN users ON task.user_id=users.id 
                                            WHERE users.id=? AND task.task_status=?",array($login_id,"2"));
                                        for ($i=0; $i < COUNT($disp_tasks_pending); $i++) { 
                                            echo "<tr>
                                                    <td>".$disp_tasks_pending[$i]['task_id']."</td>
                                                    <td>".$disp_tasks_pending[$i]['name']."</td>
                                                    <td>".$disp_tasks_pending[$i]['position']."</td>
                                                    <td>".$disp_tasks_pending[$i]['title']."</td>
                                                    <td>
                                                        <details>
                                                            ".$disp_tasks_pending[$i]['description']."
                                                        </details>
                                                    </td>
                                                    <td>".$disp_tasks_pending[$i]['assign_by']."</td>
                                                    ".getTaskStatus($disp_tasks_pending[$i]['task_status'])."
                                                    <td>".date("F d, Y",strtotime($disp_tasks_pending[$i]['task_date']))."</td>
                                                    <td>".date("F d, Y", strtotime($disp_tasks_pending[$i]['due_date']))."</td>
                                                    <td>
                                                        <a class='mr-1 back_to_new_task'
                                                            data-task-id=".$disp_tasks_pending[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_pending[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-left'></span>
                                                        </a>
                                                        <a class='mr-1 move_to_ongoing_task'
                                                            data-task-id=".$disp_tasks_pending[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_pending[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-right'></span>
                                                        </a>
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
                                    $disp_tasks_ongoing = retrieve("SELECT
                                        CONCAT(users.firstname, ' ', users.lastname) AS name,
                                            users.position AS position,
                                            users.level AS level,
                                            task.title AS title,
                                            task.id AS task_id,
                                            task.description AS description,
                                            task.assign_by AS assign_by,
                                            task.task_status AS task_status,
                                            task.task_date AS task_date,
                                            task.due_date AS due_date 
                                            FROM task LEFT JOIN users ON task.user_id=users.id 
                                            WHERE users.id=? AND task.task_status=?",array($login_id,"3"));
                                        for ($i=0; $i < COUNT($disp_tasks_ongoing); $i++) { 
                                            echo "<tr>
                                                    <td>".$disp_tasks_ongoing[$i]['task_id']."</td>
                                                    <td>".$disp_tasks_ongoing[$i]['name']."</td>
                                                    <td>".$disp_tasks_ongoing[$i]['position']."</td>
                                                    <td>".$disp_tasks_ongoing[$i]['title']."</td>
                                                    <td>
                                                        <details>
                                                            ".$disp_tasks_ongoing[$i]['description']."
                                                        </details>
                                                    </td>
                                                    <td>".$disp_tasks_ongoing[$i]['assign_by']."</td>
                                                    ".getTaskStatus($disp_tasks_ongoing[$i]['status'])."
                                                    <td>".date("F d, Y",strtotime($disp_tasks_ongoing[$i]['task_date']))."</td>
                                                    <td>".date("F d, Y", strtotime($disp_tasks_ongoing[$i]['due_date']))."</td>
                                                    <td>
                                                        <a class='mr-1 back_to_pending_task'
                                                            data-task-id=".$disp_tasks_ongoing[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_ongoing[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-left'></span>
                                                        </a>
                                                        <a class='mr-1 move_to_complete_task'
                                                            data-task-id=".$disp_tasks_ongoing[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_ongoing[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-right'></span>
                                                        </a>
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
                                    $disp_tasks_complete = retrieve("SELECT
                                        CONCAT(users.firstname, ' ', users.lastname) AS name,
                                            users.position AS position,
                                            users.level AS level,
                                            task.title AS title,
                                            task.id AS task_id,
                                            task.description AS description,
                                            task.assign_by AS assign_by,
                                            task.task_status AS task_status,
                                            task.task_date AS task_date,
                                            task.due_date AS due_date 
                                            FROM task LEFT JOIN users ON task.user_id=users.id 
                                            WHERE users.id=? AND task.task_status=?",array($login_id,"4"));
                                        for ($i=0; $i < COUNT($disp_tasks_complete); $i++) { 
                                            echo "<tr>
                                                    <td>".$disp_tasks_complete[$i]['task_id']."</td>
                                                    <td>".$disp_tasks_complete[$i]['name']."</td>
                                                    <td>".$disp_tasks_complete[$i]['position']."</td>
                                                    <td>".$disp_tasks_complete[$i]['title']."</td>
                                                    <td>
                                                        <details>
                                                            ".$disp_tasks_complete[$i]['description']."
                                                        </details>
                                                    </td>
                                                    <td>".$disp_tasks_complete[$i]['assign_by']."</td>
                                                    ".getTaskStatus($disp_tasks_complete[$i]['task_status'])."
                                                    <td>".date("F d, Y",strtotime($disp_tasks_complete[$i]['task_date']))."</td>
                                                    <td>".date("F d, Y", strtotime($disp_tasks_complete[$i]['due_date']))."</td>
                                                    <td>
                                                        <a class='mr-1 back_to_ongoing_task'
                                                            data-task-id=".$disp_tasks_complete[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_complete[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-left'></span>
                                                        </a>
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
                                    $disp_tasks_onhold = retrieve("SELECT
                                        CONCAT(users.firstname, ' ', users.lastname) AS name,
                                            users.position AS position,
                                            users.level AS level,
                                            task.title AS title,
                                            task.id AS task_id,
                                            task.description AS description,
                                            task.assign_by AS assign_by,
                                            task.task_status AS task_status,
                                            task.task_date AS task_date,
                                            task.due_date AS due_date 
                                            FROM task LEFT JOIN users ON task.user_id=users.id 
                                            WHERE users.id=? AND task.task_status=?",array($login_id,"5"));
                                        for ($i=0; $i < COUNT($disp_tasks_onhold); $i++) { 
                                            echo "<tr>
                                                    <td>".$disp_tasks_onhold[$i]['task_id']."</td>
                                                    <td>".$disp_tasks_onhold[$i]['name']."</td>
                                                    <td>".$disp_tasks_onhold[$i]['position']."</td>
                                                    <td>".$disp_tasks_onhold[$i]['title']."</td>
                                                    <td>
                                                        <details>
                                                            ".$disp_tasks_onhold[$i]['description']."
                                                        </details>
                                                    </td>
                                                    <td>".$disp_tasks_onhold[$i]['assign_by']."</td>
                                                    ".getTaskStatus($disp_tasks_onhold[$i]['task_status'])."
                                                    <td>".date("F d, Y",strtotime($disp_tasks_onhold[$i]['task_date']))."</td>
                                                    <td>".date("F d, Y", strtotime($disp_tasks_onhold[$i]['due_date']))."</td>
                                                    <td>
                                                        <a class='mr-1 back_to_complete_task'
                                                            data-task-id=".$disp_tasks_onhold[$i]['task_id']."
                                                            data-current-status=".$disp_tasks_onhold[$i]['task_status']."
                                                        >
                                                            <span class='fas fa-arrow-alt-circle-left'></span>
                                                        </a>
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

<?php include('includes/modal.php'); ?>  
<?php include('includes/footer.php'); ?>
<script>
$(document).ready(function(){

    $('.datepicker').pickadate();

    $(".delete_task").click(function(e){
        e.preventDefault();

    
        var delete_task_id = $(this).data('task-id');
    
        Swal.fire({
            title: 'Delete Task?',
            text: 'Are you sure you want to delete this task?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(delete_task_id);
    
                $.ajax({
                    url: "./actions/delete_task.php",
                    type: "POST",
                    dataType: 'JSON',
                    data: { 
                        delete_task_id: delete_task_id,
                    },
                    success: function(response) { 
                        console.log(response);
                        Swal.fire({
                            title: response.status === 'success' ? 'Deleted!' : 'Error!',
                            text: response.message,
                            icon: response.status,
                        });
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    },
                    error: function(error) { 
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + error,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    $(".edit_task").click(function(){
        $("#edit_task_id").val($(this).attr("edit_task_id"));
        $("#edit_task_title").val($(this).attr("edit_task_title"));
        $("#edit_task_desc").val($(this).attr("edit_task_desc"));
        $("#edit_task_date").val($(this).attr("edit_task_date"));
        $("#edit_task_due").val($(this).attr("edit_task_due"));
        $("#edit_task_modal").modal("show");
    });

    $('.mdb-select').materialSelect();
    $("#tblTasksNew").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":15,
        "order": [[0, "asc"]],
    });
    $("#tblTasksPending").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":15,
        "order": [[0, "asc"]],
    });
    $("#tblTasksOngoing").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":15,
        "order": [[0, "asc"]],
    });
    $("#tblTasksCompleted").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":15,
        "order": [[0, "asc"]],
    });
    $("#tblTasksOnHold").DataTable({
        "scrollX": true,
        "info": true,
        "lengthChange": true,
        "paging": true,
        "searching": true,
        "pageLength":15,
        "order": [[0, "asc"]],
    });
});
</script>
