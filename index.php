<?php
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "dblokesh";
$conn = mysqli_connect($serverName, $userName, $password, $dbName);
$is_update = false;
$is_insert = false;
$is_delete = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn) {
        if (isset($_POST['snoEdit'])) {
            $snoValue = $_POST['snoEdit'];
            $tit = $_POST['titleEdit']; 
            $dcp = $_POST['descEdit'];
            if (strlen($tit) > 0 && strlen($dcp) > 0) {
                $sql = "UPDATE `tasks` SET `title` = '$tit', `description` = '$dcp' WHERE `tasks`.`sno` = $snoValue;";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $is_update = true;
                }
            }
        }
        if(isset($_POST["delSno"])) {
            $snoValue = $_POST["delSno"];
            $sql = "DELETE FROM tasks WHERE `tasks`.`sno` = $snoValue;";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $is_delete = true;
            }
        } 
        if(isset($_POST["title"])) {
            $tit = $_POST['title'];
            $dcp = $_POST['desc'];
            if (strlen($tit) > 0 && strlen($dcp) > 0) {
                $sql = "INSERT INTO tasks (title, description, tstamp) VALUES ('$tit', '$dcp', current_timestamp());";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $is_insert = true;
                }
            }
        }
    }
}    
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!-- data tables css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
</head>
<body>
    <!-- Update Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Your Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Add Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                placeholder="Enter Task Title" required>
                        </div>
                        <div class="mb-3">
                            <label for="descEdit" class="form-label">Task Description</label>
                            <textarea class="form-control" id="descEdit" name="descEdit" rows="4"></textarea>
                        </div>
                </div>
                <div class="modal-footer d-block mr-auto">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Update Task">
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete model -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3>Are You Sure Do You Want to Delete Task ?</h3>    
                    <form action="index.php" method="post">
                        <input type="hidden" name="delSno" id="delSno">
                </div>
                <div class="modal-footer d-block mr-auto">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <input type="submit" class="btn btn-primary" value="Yes">
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>Smart Task Manager</h2>
        <form action="index.php" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Add Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Task Title" required>
            </div>
            <div class="mb-3">
                <label for="descp" class="form-label">Task Description</label>
                <textarea class="form-control" id="descp" name="desc" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" value="Add Task">
            </div>
        </form>
    </div>
    <div class="container">
        <?php
        if ($is_update) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                   <strong>Task Updated</strong>
                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   </div>';     
        }
        else if($is_insert)
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong>Task Inserted</strong>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>';
        }
        else if($is_delete)
        {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong>Task Deleted</strong>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>';
        }
        ?>
    </div>
    <div class="container mb-10">
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($conn) {
                    $sql = "SELECT * FROM `tasks` ORDER BY tstamp;";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $count++;
                            echo ' <tr>
                            <th scope="row">' . $count . '</th>
                            <td>' . $row["title"] . '</td>
                            <td>' . $row["description"] . '</td>
                            <td><button class="edit btn btn-sm btn-primary" id=' . $row['sno'] . '>Edit</button> <button class="del btn btn-sm btn-primary" id='.'d'.$row['sno'].'>Delete</button></td>
                          </tr>';
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- bootstrap js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script>
        //Initialize table 
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((val) => {
        val.addEventListener("click", (e) => {
            tr = e.target.parentNode.parentNode;
            tit = tr.getElementsByTagName('td')[0].innerText;
            dec = tr.getElementsByTagName('td')[1].innerText;
            titleEdit.value = tit;
            descEdit.value = dec;
            snoEdit.value = e.target.id;
            $('#editModal').modal('toggle');
        });
    });
    deletes = document.getElementsByClassName('del');
    Array.from(deletes).forEach((val) => {
        val.addEventListener("click", (e) => {
            sno = e.target.id.substr(1, );
            delSno.value = sno;
            $('#deleteModal').modal('toggle');
        });
    });
    </script>
</body>
</html>