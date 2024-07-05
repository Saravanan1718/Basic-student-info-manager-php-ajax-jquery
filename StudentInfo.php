<?php
include "dbconfig.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['operation'])) {
        $operation = $_POST['operation'];
        switch ($operation) {
            case 'insert':
                createRecord();
                break;
            case 'update':
                updateRecord();
                break;
            default:
                break;
        }
        showTable();
        exit();
    }
}
else if($_SERVER["REQUEST_METHOD"] =="GET"){
    if(isset($_GET['operation'])){
        $operation =$_GET['operation'];
        switch($operation){
            case 'edit':
                showForm();  
                 exit();
                break;
            case 'delete':
                deleteRecord();
                showTable();  
                exit();
                break;
            default:
                break;
        }
        
        showTable();  
        exit();
    }
} 
function createRecord()
{
    global $conn;

    if (isset($_POST['name']) && isset($_POST['age']) && isset($_POST['email'])) {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $email = $_POST['email'];

        $sql = "INSERT INTO `studentInfo`(`Name`, `Age`, `Email`) VALUES ('$name','$age','$email')";
        $result = $conn->query($sql);

        if ($result === TRUE) {
            
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

function updateRecord()
{
    global $conn;

    if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['age']) && isset($_POST['email'])) {
        $stu_id = $_POST['id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $email = $_POST['email'];

        $sql = "UPDATE `studentInfo` SET `Name`='$name',`Age`='$age',`Email`='$email' WHERE `ID`='$stu_id'";
        $result = $conn->query($sql);

        if ($result === TRUE) {
           
            
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

function deleteRecord()
{
    global $conn;
    if (isset($_GET['id'])) {
        $stu_id = $_GET['id'];
        $sql = "DELETE FROM studentInfo WHERE ID ='$stu_id'";
        $result = $conn->query($sql);

        if ($result === TRUE) {

            
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
function showForm()
{
    global $conn;

    $id = "";
    $name = "";
    $age = "";
    $email = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['operation']) && $_GET['operation'] == "edit") {
        if (isset($_GET['id'])) {
            $stu_id = $_GET['id'];

            $sql = "SELECT `Name`, `Age`, `Email` FROM `studentInfo` WHERE `ID`='$stu_id'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $stu_id;
                $name = $row['Name'];
                $age = $row['Age'];
                $email = $row['Email'];
            }
        }
    }

    ?>
    <div class="form-container">
    <form id="student-form" method="post">
            <input type="hidden" id="id" name="Id" value="<?php echo $id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder="Name"><br><br>
            <label for="age">Age:</label>
            <input type="text" id="age" name="age" value="<?php echo $age; ?>" placeholder="Age"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email"><br><br><br>
        </form>
    </div>
    <div class="button-container">
            <button type="button" id="insert-btn" form="student-form" name="operation" value="insert" class="button">Insert</button>
            <button type="button" id="update-btn" class="button">Update</button> 
            <br><br> 
            
    </div>

    <?php
}

function showTable()
{
    global $conn;

    ?>
    
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id= "table-body">
        <?php
        $sql = "SELECT * FROM studentInfo";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Name']; ?></td>
                    <td><?php echo $row['Age']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td>
                    <a href="#" class="edit-link" data-id="<?php echo $row['ID']; ?>">Edit</a>
                    <a href="#" class="delete-link" data-id="<?php echo $row['ID']; ?>">Delete</a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
    <script>

$(document).ready(function() {
    $(".edit-link").click(function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "GET",
            url: "http://localhost/Saravana\'sWorkspace/AllWorking.php",
            data: {
                id: id,
                operation: "edit"
            },
            success: function(result) {
                $("#form-id").html(result);
                
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });

    
    $(".delete-link").click(function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "GET",
            url: "http://localhost/Saravana\'sWorkspace/AllWorking.php",
            data: {
                id: id,
                operation: "delete"
            },
            success: function(data) {
                 $("#form-table").html(data);
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });
});

    </script>
 
    <?php
}
?>
   <style>
         table {
                width: 100%;
        }
        th, td {
                padding: 10px;
                text-align: center;
        }
    </style>
    <style>
        .form-container {
                border: 5px outset red;
                background-color: lightblue;
                text-align: center;
                margin-bottom: 40px;
                padding: 20px;
        }
        .button-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-top: 20px;
        }
        .button {
                border: 2px solid red;
                color: red;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                background-color: white;
            }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
        $('#insert-btn').click(function(e){
        e.preventDefault(); 
        var formData = {
            name: $("#name").val(),
            age: $("#age").val(),
            email: $("#email").val(),
            operation: 'insert'
        };
        $.ajax({
            type: 'POST',
            url: 'http://localhost/Saravana\'sWorkspace/AllWorking.php',
            data: formData,
            success: function(data) {
                $("#form-table").html(data); 
                $('#student-form')[0].reset();
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error); 
            }
        });
    });
});
   
    $(document).on('click', '#update-btn', function(e) {
    e.preventDefault();
    var id = $("#id").val();
    var name = $("#name").val();
    var age = $("#age").val();
    var email = $("#email").val();
    $.ajax({
        type: 'POST',
        url: 'http://localhost/Saravana\'sWorkspace/AllWorking.php',
        data: {
            id: id,
            name: name,
            age: age,
            email: email,
            operation: 'update'
        },
        success: function(data) {
            $("#form-table").html(data); 
            //$('#student-form')[0].reset();
            resetForm(); 
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

function resetForm() {
    $('#id').val('');
    $('#name').val('');
    $('#age').val('');
    $('#email').val('');
}

    </script>
    <html>
        <div id='form-id'><?php showForm();?></div>
        <div id='form-table'><?php showTable();?></div>
     </html>
