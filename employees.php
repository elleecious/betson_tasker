<?php include('includes/header.php') ?>
<?php $page_title="Betson Tasker"; ?>
    <div class="row mx-auto mt-3">
        <div class="col-md-12 mb-2">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <div class="card">
                        <div class="card-header p-3 bg-primary text-white">
                            Betson Employees
                        </div>
                        <div class="d-flex flex-row justify-content-center mt-5">
                            <div class="p-4 mr-4 bg-primary text-white text-center">Work Mode <br><span>0</span></div>
                            <div class="p-4 mr-4 bg-success text-white text-center">Lunch Break <br><span>0</span></div>
                            <div class="p-4 mr-4 bg-warning text-white text-center">Shork Break <br><span>0</span></div>
                        </div>
                        <hr>
                        <div class="card-body mt-3">
                            <div class="row text-center">
                                <?php
                                    $employee_list=retrieve("SELECT * FROM users",array());
                                    for ($i=0; $i < COUNT($employee_list); $i++) { 
                                        echo "
                                            <div class='col-md-2 mt-3'>
                                                <div class='p-3 text-white bg-primary'>
                                                    <h3>ON LUNCH BREAK</h3>
                                                    <hr class='divider'>
                                                    <h5>".$employee_list[$i]['firstname']." ".$employee_list[$i]['lastname']."</h5>
                                                </div>
                                            </div>
                                        ";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include('includes/footer.php') ?>