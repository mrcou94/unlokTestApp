<?php
include "includes/config.php";
session_start();
if (!isset($_SESSION["user_email"])) {
    header("Location: index.php");
    die();
}

?>


<!doctype html>
<html lang="en">

<head>
    <?php getHead(); ?>
</head>

<body>
    <?php getHeader(); ?>
    <div class="container">
        <h1 class="mb-4 text-center fw-bold">Tasks</h1>
        <div class="row">
            <?php 
            // Get User Id based on user email
            $sql = "SELECT id FROM users WHERE email='{$_SESSION["user_email"]}'";
            $res = mysqli_query($conn, $sql);
            $sql1 = "";
            $count = mysqli_num_rows($res);
            if ($count > 0) {
                $row = mysqli_fetch_assoc($res);
                $user_id = $row["id"];
            } else {
                $user_id = 0;
            }
            if (isset($_POST["date_submit"])) {
                $inital_date = mysqli_real_escape_string($conn, $_POST["initial_date"]);
                $end_date = mysqli_real_escape_string($conn, $_POST["end_date"]);
                //get from dates      
                $sql1 = "SELECT * FROM tasks WHERE user_id='{$user_id}' AND date BETWEEN '{$inital_date}' AND '{$end_date}';";
            }else{
                $sql1 = "SELECT * FROM tasks WHERE user_id='{$user_id}' ORDER BY id DESC";
                
            }
            $res1 = mysqli_query($conn, $sql1);
            if (mysqli_num_rows($res1) > 0) {
               
            ?>
            <div id="toolbar" class="select">

            <div class="col-md-12"  >
                <div class="row" style="margin-bottom: 2%;">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <form action="" class="row row-cols-lg-auto g-3 align-items-center" method="POST">
                            <div class="form-group">
                                <input type="date" class="form-control" placeholder="Initial Date" name="initial_date">
                            </div>
                            <div class="form-group">
                                <input type="date" class="form-control" placeholder="End date" name="end_date">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="date_submit" class="btn btn-primary me-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                
            
            <table id="table" class="display" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
                
            </div>
            <?php  } else { echo "<h1 class='text-danger text-center fw-bold'>Todos are not available!</h1>"; } ?>
        </div>
    </div>

    
    
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
             
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>


    <script>
        var dataSet = <?php getTodo($res1); ?>;

        $(document).ready(function () {
            
            
            $('#table').DataTable({
                data: dataSet,
                dom: "<'row customDttables' <'col-sm-6 col-lg-6 dt-left' l><'col-sm-6 col-lg-6 dt-right' f>> <'row customDttables' <'col-sm-12 col-md-12 col-lg-12'>><t><ip>",
                columns: [
                    { data: 'id' },
                    { data: 'title' },
                    { data: 'comment' },
                    { data: 'date.' },
                    { data: 'time' },
                    { data: 'actions' },
                ],
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                select: 'single'
               
            });

            var table = $('#table').DataTable();
            $('#table tbody').on('click', 'tr', function () {
                $(this).toggleClass('selected');
            });
        
            $('#button').click(function () {
                console.log(table.rows('.selected').data());
            });
        });
    
        
    </script>
</body>

</html>