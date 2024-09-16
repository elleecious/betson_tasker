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
                            <textarea class="form-control md-textarea" name="task_desc" id="task_desc" rows="5" style="resize:none;"></textarea>
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
                            <label for="due_date">Due Date</label>
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

  <div class="modal fade" id="edit_task_modal" tabindex="-1" role="dialog" aria-labelledby="editTaskLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editTaskLabel">Edit Task</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="post" id="frmEditTask">
                <div class="row">
                    <input type="text" name="edit_task_id" id="edit_task_id" hidden>
                    <div class="col-md-12">
                        <div class="md-form">
                            <input class="form-control form-control-md" type="text" name="edit_task_title" id="edit_task_title">
                            <label class="text-dark" id="edit_task_title">Title</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="md-form">
                            <textarea class="form-control md-textarea" name="edit_task_desc" id="edit_task_desc" rows="5" style="resize:none;"></textarea>
                            <label class="text-dark" for="edit_task_desc">Description</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="md-form">
                            <input placeholder="Selected date" type="text" name="edit_task_date" id="edit_task_date" class="form-control datepicker">
                            <label for="edit_task_date">Assigned Date</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="md-form">
                            <input placeholder="Selected date" type="text" name="edit_task_due" id="edit_task_due" class="form-control datepicker">
                            <label for="edit_task_due">Due Date</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto" name="save_task" id="save_task">Save</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="delete_task_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header primary-color text-white">
        <h5 class="modal-title w-100 text-white">Delete Task</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
            <div class="row mt-3">
              <input type="text" name="delete_task_id" id="delete_task_id" hidden>
              <div class="col-md-12">
                <h6>Are you sure you want to delete this task?</h6>
              </div>
            </div>
            <div class="d-flex flex-row">
                <button type="submit" class="btn btn-success btn-rounded btn-sm" name="delete_task" id="delete_task">YES</button>
                <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" title="Close">NO</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>