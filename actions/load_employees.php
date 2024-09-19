<?php
include('../includes/connect.php');

header('Content-Type: application/json');


$get_employees = retrieve("SELECT * FROM users", array());

if (!empty($get_employees)) {
    $employee_list = '';
    foreach ($get_employees as $employee) {
        $employee_list .= "
            <div class='col-12 col-sm-6 col-md-4 col-lg-2 mt-2'>
                <div class='p-3 text-white bg-primary'>
                    <h3>ON LUNCH BREAK</h3>
                    <hr class='divider'>
                    <h5>".$employee['firstname']." ".$employee['lastname']."</h5>
                </div>
            </div>
        ";
    }
    
    // Return a JSON response with the employee list
    $response = array('status' => 'success', 'employees' => $employee_list);
} else {
    $response = array('status' => 'error', 'message' => 'No employees found.');
}

echo json_encode($response);
?>
