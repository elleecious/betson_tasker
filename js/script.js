$(document).ready(function() {
    
    var breakTimeLeft = 3600;
    var timerInterval;

    function startBreakTimer(duration) {
        breakTimeLeft = duration;
        timerInterval = setInterval(function() {
            var minutes = Math.floor(breakTimeLeft / 60);
            var seconds = breakTimeLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
    
            if (breakTimeLeft === 60) {
                Swal.fire({
                    title: "Break Time",
                    text: "You should end your short break or else you will don't ",
                    icon: "warning",
                    confirmButtonText: "Okay",
                });
            }
    
            if (--breakTimeLeft < 0) {
                clearInterval(timerInterval);
                Swal.fire('Break Over', 'Your break time has ended.', 'info');
                $('#breakButton').show();
                $('#endBreakButton').hide();
    
                endBreakAutomatically();
                return;
            }
            $('#timer').text(minutes + ":" + seconds);
        }, 1000);
    }
    
    function endBreakAutomatically() {
        $.ajax({
            url: './actions/end_break.php',
            type: 'POST',
            dataType: 'JSON',
            data: { action: 'automatic_end_break', break_time: breakTimeLeft },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Break Ended Automatically', response.message, 'info');
                    $("#status").text('Status: Back to Work');
                    $('#breakButton').show();
                    $('#endBreakButton').hide();
                    clearInterval(timerInterval);
                } else {
                    Swal.fire('Error', response.message, 'error');
                    console.log('Error ending break automatically: ', response.message);
                }
            },
            error: function(error) {
                console.log('Error ending break automatically: ', error);
            }
        });
    }

    function resetBreaks(){

        const currentTime = new Date();
        const hours = currentTime.getHours();
        const minutes = currentTime.getMinutes();

        if (hours === 21 && minutes === 0) {
            $.ajax({
                url: './actions/reset_breaks.php',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    if (response.status === 'success') {
                        console.log("Message: "+response.message);
                    } else if (response.status === 'error') {
                        console.log("Message: "+response.message);
                    }
                },
                error: function (error) { 
                    console.log('An error occured: ', error);
                }
            });
        }
    }
    setInterval(resetBreaks, 60000); // every 1 minute



    $.ajax({
        url: './actions/check_break_status.php',
        type: 'GET',
        success: function(response) {
            if (response.status === 'on_break' && response.time_remaining <= 0) {
                breakTimeLeft = response.time_remaining;
                console.log("Break time left: ", breakTimeLeft);

                if (breakTimeLeft > 0 && !isNaN(breakTimeLeft)) {
                    startBreakTimer(breakTimeLeft);  
                    $('#status').text('Status: On Break');
                    $('#breakButton').hide();
                    $('#endBreakButton').show();
                } else {
                    console.error('Invalid break time left:', breakTimeLeft);
                }
            } else if (response.status === 'active') {
                $('#breakButton').show();
                $('#endBreakButton').hide();
                $('#status').text('Status: Active');
                console.log('User is active, not on break');
            } else {
                console.log('Unknown status:', response.status);
            }
        },
        error: function(error) {
            console.error('Failed to retrieve break status:', error);
        }
    });

    $("#breakButton").click(function() {


        $.ajax({
            url: './actions/start_break.php',
            type: 'POST',
            data: { action: 'start_break' },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Success', response.message, 'success');
                    $('#status').text('Status: On Break');
                    $('#breakButton').hide();
                    $('#endBreakButton').show();
    
                    breakTimeLeft = response.break_time ? response.break_time : 3600;
                    startBreakTimer(breakTimeLeft);

                    console.log("Start Break Time Left on Button: ", breakTimeLeft);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(error) {
                console.error('Failed to start break:', error);
                Swal.fire('Error', 'An error occurred while starting the break: ' + error, 'error');
            }
        });
    });

    $("#endBreakButton").click(function() {
        $.ajax({
            url: './actions/end_break.php',
            type: 'POST',
            data: { action: 'end_break', break_time: breakTimeLeft },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire('Break Ended', 'Your short break has ended successfully. Please return to work.', 'success');
                    $('#status').text('Status: Back to Work');
                    $('#breakButton').show();
                    $('#endBreakButton').hide();
                    clearInterval(timerInterval);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while ending the break.', 'error');
            }
        });
    });
    
    

    function updateBreaks() {
        $.ajax({
            url: './actions/display_breaks.php',
            type: 'GET',
            cache:false,
            success: function(response) {
                if (response.status === 'success') {
                    var employeeList = '';
                    var onBreak = false;
    
                    response.employees.forEach(function(employee) {
                        if (employee.time_remaining > 0) {
                            employeeList += "<div class='col-12 col-sm-6 col-md-4 col-lg-2 mt-2'>" +
                                            "<div class='p-3 text-white bg-warning'>" +
                                                "<h3>ON BREAK</h3>" +
                                                "<hr class='divider'>" +
                                                "<h5>" + employee.firstname + " " + employee.lastname + "</h5>" +
                                            "</div>" +
                                            "</div>";
                            onBreak = true;
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
    
    setInterval(updateBreaks, 4000);
 

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
    
    $("#save_profile").click(function(e){

        e.preventDefault();

        $.ajax({
            url: "./actions/save_profile.php",
            type: 'POST',
            data: { 
                edit_id:$("#edit_id").val(),
                edit_lastname:$("#edit_lastname").val(),
                edit_firstname:$("#edit_firstname").val(),
                edit_position:$("#edit_position").val(),
                edit_username:$("#edit_username").val(),
                edit_password:$("#edit_password").val(),
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
            },
            error: function(error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
    });

    $("#save_password").click(function(e){
        e.preventDefault();

        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();

        if (newPassword !== confirmPassword) {
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }

        $.ajax({
            url: "./actions/save_password.php",
            type: 'POST',
            data: { 
                current_password:currentPassword,
                new_password:newPassword
            },
            dataType: 'JSON',
            success: function(response) { 
                Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status === 'success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                });
                $("#frmChangePassword")[0].reset();
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
                setTimeout(function(){
                    location.reload();
                }, 1000);
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
                setTimeout(function(){
                    location.reload();
                }, 1000);
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
            type: 'POST',
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

    $.ajax({
        url: "./actions/check_task.php",
        type: 'GET',
        success: function(response) {
            var taskNotificationMessage = '';
            
            if (response.status === 'success') {
                response.data.forEach(task => {
                    taskNotificationMessage += `
                        <h5 class="text-center">${task.title}</h5>
                        <hr class="divider">`;
                });
            } else {
                taskNotificationMessage += `
                    <h5 class="text-center">No tasks today</h5>`;
            }
            
            $("#taskNotification").html(taskNotificationMessage);
        },
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


    if (localStorage.getItem('nightMode') === 'enabled') {
        $("body").addClass('bg-dark text-white');
        $(".jumbotron").addClass('bg-dark text-white');
        $(".container").addClass('bg-dark text-white');
        $(".card").addClass('bg-dark text-white');
        $("section").addClass('bg-dark');
    }

    $("body").fadeIn(0);

    $("#nightMode").click(function(){
		$("body").toggleClass('bg-dark text-white');
		$(".jumbotron").toggleClass('bg-dark text-white');
		$(".container").toggleClass('bg-dark text-white');
        $(".card").toggleClass('bg-dark text-white');
        $("section").toggleClass('bg-dark');

        if ($("body").hasClass("bg-dark text-white")) {
            localStorage.setItem("nightMode","enabled");
        } else {
            localStorage.setItem("nightMode",'disabled');
        }
	});

});