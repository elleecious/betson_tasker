$(document).ready(function() {
    
    // let timerInterval;
    // let breakTimeLeft = localStorage.getItem('remainingBreakTime') ? parseInt(localStorage.getItem('remainingBreakTime')) : 3600;
    var breakTimeLeft = 3600; // Default 1 hour in seconds
    var timerInterval;

    function updateTimerDisplay(timeLeft) {
        var minutes = Math.floor(timeLeft / 60);
        var seconds = timeLeft % 60;
    
        $('#timer').text(minutes + ":" + (seconds < 10 ? '0' : '') + seconds);
    }
    
    

    if (localStorage.getItem('remainingBreakTime')) {
        breakTimeLeft = parseInt(localStorage.getItem('remainingBreakTime'), 10);
        updateTimerDisplay(breakTimeLeft);
        startBreakTimer();
    }

    function startBreakTimer(initialTime) {
        if (initialTime) {
            breakTimeLeft = initialTime;
        }
    
        timerInterval = setInterval(function() {
            if (breakTimeLeft <= 0) {
                clearInterval(timerInterval);
                $('#status').text('Status: No Break Time Left');
                $('#breakButton').prop('disabled', true);
                $('#endBreakButton').hide();
                return;
            }
            
            breakTimeLeft--;
            updateTimerDisplay(breakTimeLeft);
            localStorage.setItem('breakTimeLeft', breakTimeLeft);
        }, 1000);
    }
    

    $("#breakButton").click(function() {
        $.ajax({
            url: './actions/update_break_status.php',
            method: 'POST',
            data: { action: 'start_break' },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Success', response.message, 'success');
                    $('#status').text('Status: On Break');
                    $('#breakButton').hide();
                    $('#endBreakButton').show();

                    let savedBreakTime = localStorage.getItem('breakTimeLeft');
                    if (savedBreakTime !== null && savedBreakTime > 0) {
                        breakTimeLeft = parseInt(savedBreakTime, 10);
                    } else {
                        breakTimeLeft = 3600;
                    }

                    startBreakTimer(breakTimeLeft);
                    getRemainingBreakTime();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            }
        });
    });
    
    $("#endBreakButton").click(function() {

        clearInterval(timerInterval);
        localStorage.setItem('breakTimeLeft', breakTimeLeft);

        $.ajax({
            url: './actions/update_break_status.php',
            method: 'POST',
            data: { action: 'end_break' },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Break Ended', 'Your short break has ended successfully. Please back to work', 'success');
                    $('#status').text('Status: Back to Work');
                    $('#breakButton').show();
                    $('#endBreakButton').hide();
                    updateTimerDisplay(breakTimeLeft)
                    //localStorage.setItem('remainingBreakTime', breakTimeLeft);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while ending the break.', 'error');
            }
        });
    });

    function getRemainingBreakTime() {
        $.ajax({
            url: './actions/get_remaining_break_time.php',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let timeRemaining = response.time_remaining;
                    updateTimerDisplay(timeRemaining);
                    startBreakTimer();
                } else {
                    console.error(response.message);
                }
            },
            error: function() {
                console.error('Failed to retrieve break time.');
            }
        });
    }
    
    

    function updateBreaks() {
        $.ajax({
            url: './actions/display_breaks.php',
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    var employeeList = '';
                    var onBreak = false;

                    response.employees.forEach(function(employee) {
                        if (employee.break_start) {
                            var breakStart = new Date(employee.break_start);
                            var now = new Date();
                            var interval = Math.floor((now - breakStart) / 1000);
                            var breakDuration = 3600;
                            var timeLeft = breakDuration - interval;
                            
                            if (timeLeft >= 0) {
                                var minutes = Math.floor(timeLeft / 60);
                                var seconds = timeLeft % 60;
                                employeeList += "<div class='col-12 col-sm-6 col-md-4 col-lg-2 mt-2'>" +
                                                "<div class='p-3 text-white bg-warning'>" +
                                                    "<h3>ON BREAK</h3>" +
                                                    "<hr class='divider'>" +
                                                    "<h5>" + employee.firstname + " " + employee.lastname + "</h5>" +
                                                    "<div>" + minutes + ":" + (seconds < 10 ? '0' : '') + seconds + "</div>" +
                                                "</div>" +
                                                "</div>";
                                onBreak = true;
                            }
                        }
                    });

                    if (!onBreak) {
                        employeeList += `
                            <div class='col-12 mt-2'>
                                <div class='jumbotron bg-primary text-white p-5'>
                                    <h3 class='display-4'>THEY ARE ALL WORKING</h3>
                                </div>
                            </div>`;
                    }
        
                    $('#employee_list').html(employeeList);
                } else {
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    }
        
    setInterval(updateBreaks, 1000);
    
    
    $.ajax({
        url: './actions/check_break_status.php',
        method: 'GET',
        cache:false,
        success: function(response) {
            if (response.status === 'on_break') {
                $('#breakButton').hide();
                $('#status').text('Status: On Break');
                $('#endBreakButton').show();
                if (data.time_remaining) {
                    startBreakTimer(response.time_remaining);
                }
            } else {
                $('#status').text('Status: Working');
                $('#breakButton').show();
                $('#endBreakButton').hide();
            }
        },
        error: function(error) {
            console.error('Failed to check break status.', error);
        }
    });
    
    
    


    //Login
    $("#btnLogin").click(function(e){
        e.preventDefault();

        $.ajax({
            url: './actions/login.php',
            type: 'POST',
            data: {
                username: $('#username').val(),
                password: $('#password').val()
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status === 'success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (response.status === 'success') {
                        window.location.href = "home.php";
                    }
                })
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
    });


    //Create Account
    $("#register").click(function(e) {
        e.preventDefault();
    
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
    
        if (password === confirm_password) {
            $.ajax({
                url: "./actions/create_account.php",
                type: 'POST',
                data: {
                    lastname:$("#lastname").val(),
                    firstname:$("#firstname").val(),
                    position:$("#position").val(),
                    level: $("#level").val(),
                    username:$("#username").val(),
                    password:$("#password").val(),
                },
                dataType: 'JSON',
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        title: response.status === 'success' ? 'Success!' : 'Error!',
                        text: response.message,
                        icon: response.status,
                        confirmButtonText: 'OK'
                    });
                    $("#frmRegistration")[0].reset();
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
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    $("#add_task").click(function(e){
        e.preventDefault();

        $.ajax({
            url: "./actions/add_task.php",
            type: 'POST',
            data: { 
                task_title:$("#task_title").val(),
                task_desc:$("#task_desc").val(),
                task_date:$("#task_date").val(),
                due_date: $("#due_date").val(),
            },
            dataType: 'JSON',
            success: function(response) { 
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                });
                $("#frmCreateTask")[0].reset();
                $("#modalCreateTask").modal('hide');
            },
            error: function(error){
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
    });

    $("#save_task").click(function(e){
        e.preventDefault();

        $.ajax({
            url: "./actions/save_task.php",
            type: 'POST',
            data: { 
                edit_task_id: $("#edit_task_id").val(),
                edit_task_title:$("#edit_task_title").val(),
                edit_task_desc:$("#edit_task_desc").val(),
                edit_task_date:$("#edit_task_date").val(),
                edit_task_due:$("#edit_task_due").val(),
            },
            dataType: 'JSON',
            success: function(response) { 
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                });
                $("#edit_task_modal").modal('hide');
            },
            error: function(error){ 
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })

    });

    $("#delete_task").click(function(e){ 
        e.preventDefault();

        $.ajax({
            url: "./actions/delete_task.php",
            type: 'POST',
            data:{
                delete_task_id:$("#delete_task_id").val()
            },
            dataType:'JSON',
            success:function (response) { 
                console.log(response);
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status,
                    confirmButtonText: 'OK'
                });
                $("#delete_task_modal").modal('hide');
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
     });


     $(".back_to_new_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
        
        updateTaskStatus(taskId, currentStatus, 'backward');
    });

    $(".back_to_pending_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
        
        updateTaskStatus(taskId, currentStatus, 'backward');
    });

    $(".back_to_ongoing_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
        
        updateTaskStatus(taskId, currentStatus, 'backward');
    });


    $(".move_to_pending_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
        
        updateTaskStatus(taskId, currentStatus, 'forward');

    });
    
    $(".move_to_ongoing_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
    
        updateTaskStatus(taskId, currentStatus, 'forward');
    });

    $(".move_to_complete_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
    
        updateTaskStatus(taskId, currentStatus, 'forward');
    });

    $(".back_to_complete_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
        
        updateTaskStatus(taskId, currentStatus, 'backward');
    });

    $(".paused_task").click(function() {
        var taskId = $(this).attr("data-task-id");
        var currentStatus = $(this).attr("data-current-status");
    
        updateTaskStatus(taskId, currentStatus, 'paused');
    });

    function updateTaskStatus(taskId, currentStatus, direction) {
        $.ajax({
            url: "./actions/update_task_status.php",
            method: 'POST',
            data: { 
                task_id: taskId, 
                current_status: currentStatus, 
                direction: direction
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Task status updated successfully!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to move task status.',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (error) { 
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
             }
        });
    }
    
    $("#nightMode").click(function(){
		$("body").toggleClass('bg-dark text-white');
		$(".jumbotron").toggleClass('bg-dark text-white');
		$(".container").toggleClass('bg-dark text-light');
        $(".card").toggleClass('bg-dark text-light');
        $(".table").toggleClass('bg-dark text-light');
	});

    $("#btnLogout").click(function(e){
        e.preventDefault();

        $.ajax({
            url: './actions/logout.php',
            type: 'POST',
            dataType: 'json', 
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Logged Out',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'An error occurred.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

});
