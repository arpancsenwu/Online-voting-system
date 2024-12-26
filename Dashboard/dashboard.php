<?php
session_start();
$voterdata = $_SESSION['voterdata'];

$conn = mysqli_connect('localhost', 'root', '', 'voterdatabase');

$query = "SELECT * FROM addcandidate";
$result = mysqli_query($conn, $query);

if ($_SESSION['voterdata']['status'] == 0) {
    $status = '<b style="color:green;">Not Voted</b>';
} else {
    $status = '<b style="color:red;">Voted</b>';
}

// Set countdown start and end dates (modify these variables to fetch from your database)
$countdownStart = "2024-07-01 10:17:00"; // Example: YYYY-MM-DD HH:MM:SS
$countdownEnd = "2024-07-01 10:30:00";   // Example: YYYY-MM-DD HH:MM:SS
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .nav-item a {
            color: whitesmoke;
        }

        .nav-item a:hover {
            color: whitesmoke;
            background: red;
            border-radius: 7px;
        }

        #main-sec {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.9);
        }

        #countdown-container {
            text-align: center;
            background-color: #343a40; /* Background color of "Welcome to the Online Voting System" */
            color: whitesmoke;
            padding: 20px;
            margin-bottom: 20px;
        }

        #countdown {
            font-size: 3rem;
            font-family: 'Arial', sans-serif; /* Modify font family here */
        }

        /* Custom styles for right menu */
        #rightMenu {
            display: none;
            position: fixed;
            right: 0;
            top: 0;
            height: 100%;
            width: 300px;
            background-color: #f8f9fa; /* Adjust background color as needed */
            z-index: 1000;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        #rightMenu h2 {
            text-align: center;
        }

        #rightMenu form {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand"><i class="fa fa-fw fa-globe"></i>Online Voting System</a>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#"><i class="fa fa-fw fa-home"></i>Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-fw fa-search"></i>Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-fw fa-envelope"></i>Contact us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="openRightMenu()"><i class="fa fa-fw fa-user"></i>Admin Login</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Right Menu for Admin Login -->
<div id="rightMenu">
    <h2>Admin Login</h2>
    <form action="Admin Login/Adminlogin.php" method="post">
        <div class="mb-3">
            <label for="adminName" class="form-label">Name</label>
            <input type="text" class="form-control" id="adminName" name="name" required>
        </div>
        <div class="mb-3">
            <label for="adminPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="adminPassword" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<!-- Display Countdown Timer -->
<div id="countdown-container">
    <h2 id="countdown-title"></h2>
    <div id="countdown"></div>
</div>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="Image/background.jfif" class="d-block w-100" height="350px" alt="...">
            <div class="carousel-caption d-md-block">
                <h1 style="text-align: center;">Welcome to the Online Voting System</h1>
            </div>
        </div>
    </div>
</div>
<br><br><br>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="card mb-3" style="max-width: 540px;">
                <div class="card-header" style="color: red;"><marquee>You can vote only one Candidate</marquee></div>
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="../VoterImg/<?php echo $voterdata['photo'] ?>" class="img-fluid rounded-start"
                             alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title" style="color: blue;">Voter Details</h5>
                            <p class="card-text">
                                <li>Name : <?php echo $voterdata['name'] ?></li>
                                <li>Mobile Number: <?php echo $voterdata['mobile'] ?></li>
                                <li>NID Number : <?php echo $voterdata['cnic'] ?></li>
                            </p>
                            <h5 class="card-title" id="voter-status">Status : <?php echo $status ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <table class="table" id="main-sec">
                <thead>
                <tr>
                    <th scope="col">Candidate Details</th>
                    <th scope="col">Symbol</th>
                    <th scope="col">Photo</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td>
                            <li>Candidate Name : <?php echo $row['cname'] ?></li>
                            <li>Party Name : <?php echo $row['cparty'] ?></li>
                            <li>Total Votes : <?php echo $row['votes'] ?></li><br>

                            <form id="vote-form-<?php echo $row['id']; ?>" action="Admin Login/vote.php" method="post">
                                <input type="hidden" name="gvotes" value="<?php echo $row['votes'] ?>">
                                <input type="hidden" name="gid" value="<?php echo $row['id'] ?>">

                                <button type="button" class="btn btn-danger vote-button"
                                        data-start="<?php echo $countdownStart; ?>"
                                        data-end="<?php echo $countdownEnd; ?>"
                                        data-voter-id="<?php echo $_SESSION['voterdata']['id']; ?>"
                                        data-candidate-id="<?php echo $row['id']; ?>"
                                >Vote
                                </button>
                            </form>
                        </td>
                        <td><img src="Admin Login/Image/<?php echo $row['symbol'] ?>"
                                 width="40%" style="border-radius: 50%;"></td>
                        <td><img src="Admin Login/Image/<?php echo $row['photo'] ?>"
                                 width="70%" style="border-radius: 10px;"></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Update countdown and message based on current time
    var x = setInterval(function () {
        var now = new Date().getTime();
        var countDownDate = new Date("<?php echo $countdownStart; ?>").getTime();
        var endDate = new Date("<?php echo $countdownEnd; ?>").getTime();

        if (now < countDownDate) {
            document.getElementById("countdown-title").innerHTML = "Voting Starts In";
            document.getElementById("countdown").innerHTML = formatCountdown(countDownDate - now);
        } else if (now >= countDownDate && now < endDate) {
            document.getElementById("countdown-title").innerHTML = "Remaining Time for Voting";
            document.getElementById("countdown").innerHTML = formatCountdown(endDate - now);
            document.getElementById("countdown-container").style.display = "block";
        } else {
            document.getElementById("countdown-title").innerHTML = "Voting Has Ended";
            document.getElementById("countdown").innerHTML = "Voting has ended.";
            document.getElementById("countdown-container").style.display = "block"; // Ensure countdown container is visible
            clearInterval(x);
        }

        // Disable or enable vote buttons based on current time
        var buttons = document.getElementsByClassName("vote-button");
        for (var i = 0; i < buttons.length; i++) {
            var start = new Date(buttons[i].getAttribute("data-start")).getTime();
            var end = new Date(buttons[i].getAttribute("data-end")).getTime();
            if (now < start || now >= end) {
                buttons[i].disabled = true;
            } else {
                buttons[i].disabled = false;
            }
        }
    }, 1000);

    // Function to format time into days, hours, minutes, seconds
    function formatCountdown(distance) {
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        return days + "d " + hours + "h " + minutes + "m " + seconds + "s";
    }

    // Function to open right menu for Admin Login
    function openRightMenu() {
        document.getElementById("rightMenu").style.display = "block";
    }
</script>
</body>
</html>
