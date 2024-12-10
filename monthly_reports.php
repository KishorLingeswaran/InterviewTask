<?php


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "interviewTask"; 


$conn = new mysqli($servername, $username, $password, $dbname);



function getFileIcon($file) {

    $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));


    switch ($file_extension) {
        case 'pdf':
            return 'fas fa-file-pdf'; 
        case 'docx':
        case 'doc':
            return 'fas fa-file-word'; 
        
        case 'xlsx':
        case 'xls':
            return 'fas fa-file-excel'; 
        case 'pptx':
        case 'ppt':
            return 'fas fa-file-powerpoint';
        default:
            return 'fas fa-file'; 
    }
}


$name = 'Kishor'; 
$sql = "SELECT report_name, report_date, uploaded_files FROM monthly_reports WHERE name = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Error preparing the statement: ' . $conn->error);
}

$stmt->bind_param("s", $name); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Reports | Glass House LLP</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="header">

    <div class="header-left">
    <i class="fa-solid fa-hourglass-end"></i>
       <span> Glass <br>House LLP</spn>
    </div>
    <div class="header-right">
        <span class="hello-admin">Hello: Admin <i class="fa-solid fa-right-from-bracket"></i></span>
    </div>
</div>

<div class="container">
    <div class="sidebar">
        <ul class="menu">
            <li><a href="#"><i class="fa-solid fa-house"></i>   Dashboard</a></li>
            <li><a href="#" class="active"><i class="fa-solid fa-dice-d6"></i>   Reports</i></a></li>
            <li><a href="#"><i class="fa-regular fa-credit-card"></i>   Payments</a></li>
            <li><a href="#"><i class="fa-solid fa-link"></i>   Links</a></li>
            <li><a href="#"><i class="fa-regular fa-newspaper"></i>   News</a></li>
            <li><a href="#"><i class="fa-solid fa-gear"></i>   Settings</a></li>
        </ul>
    </div>
    <h3>Monthly Reports</h3>
    <div class="main-content">
        <div class="form-section">
            <div class="form-container">
                <h4>Monthly Report Upload</h4>
                <p>Select respective User to upload Monthly reports</p>
                <form action="monthly_reports.php" method="POST" enctype="multipart/form-data">
                    <label for="select-name">Select Name</label>
                    <select id="select-name" name="select-name">
                 
                        <option value="Kishor">Kishor</option>
                    </select>
                    <label for="report-name">Report Name</label>
                    <input type="text" id="report-name" name="report-name" value="" required>
                    <label for="report-date">Report Date (use date picker)</label>
                    <input type="date" id="report-date" name="report-date" value="" required>
                    <label for="report-upload">Report PDF Upload (multiple pdf file upload)</label>
                    <div class="drop-zone" id="drop-zone">
                        <p> Choose / Drag and drop your files here</p>
                        <input type="file" id="file-input" name="report-upload[]" multiple style="display:none;" />
                    </div>
                   
                    <button type="submit">Submit</button>
                </form>
            </div>
            <div class="report-list">
                <h2>Previous Reports for Kishor</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Date Added</th>
                            <th>Files</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($reports)): ?>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($report['report_name']); ?></td>
                                    <td><?php echo htmlspecialchars($report['report_date']); ?></td>
                                    <td>
                                        <?php
                                        $uploaded_files = unserialize($report['uploaded_files']);
                                        if (!empty($uploaded_files)) {
                                            foreach ($uploaded_files as $file) {
                                                
                                                $file_icon_class = getFileIcon($file);
                                              
                                                echo "<i class='$file_icon_class' style='font-size: 20px;'></i> "; 
                                            }
                                        } else {
                                            echo "No files uploaded.";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-edit" style="font-size: 20px; color: #000000;"></i> 
                                        <i class="fas fa-trash-alt" style="font-size: 20px; color: #00000;"></i>
                                    
                                </td>
                          </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No reports available</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
<footer>
            &copy; 2023-24 All Rights Reserved, Glass House LLP.
        </footer>
</body>
</html>
<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    
    dropZone.addEventListener('click', function() {
        fileInput.click();  
    });
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#4CAF50'; 
    });

    dropZone.addEventListener('dragleave', function() {
        dropZone.style.borderColor = '#ccc';  
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        const files = e.dataTransfer.files;
        fileInput.files = files;  
    });
</script>
<?php
  

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "interviewTask"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = $_POST['select-name'];
    $report_name = $_POST['report-name'];
    $report_date = $_POST['report-date'];

    
    $uploaded_files = [];

    
    foreach ($_FILES['report-upload']['tmp_name'] as $key => $tmp_name) {
        $filename = $_FILES['report-upload']['name'][$key];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($filename);


        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_files[] = $target_file; 
        }
    }

    $uploaded_files_serialized = serialize($uploaded_files);

    
    $stmt = $conn->prepare("INSERT INTO monthly_reports (name, report_name, report_date, uploaded_files) VALUES (?, ?, ?, ?)");
    
  
    if ($stmt === false) {
        echo "Error preparing the statement: " . $conn->error;
        exit();
    }


    $stmt->bind_param("ssss", $name, $report_name, $report_date, $uploaded_files_serialized);


    if ($stmt->execute()) {
        echo "<p>Report uploaded successfully!</p>";
    } else {
 
        echo "<p>Error: " . $stmt->error . "</p>";
    }


    $stmt->close();
}


$conn->close();
?>