<?php
session_start();
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ART TOYS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"
        crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php include './layout/nav.php'; ?>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <!-- Sidebar -->
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: 100vh; overflow-y: auto;">
                    <?php include './layout/sidebar.php'; ?>
                </nav>
            <?php endif; ?>

            <!-- Content Area -->
            <main
                class="col-md-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '9' : '12'; ?> ms-sm-auto col-lg-<?php echo isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '10' : '12'; ?> px-md-4">
                <div id="content-area">

                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Welcome Admin</h1>
                        </div>
                    <?php else: ?>
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Welcome Customer</h1>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX script -->
    <script>
        $(document).ready(function () {
            // Function to load content when clicking nav links
            $('nav .nav-link').on('click', function (e) {
                e.preventDefault(); // Prevent default link behavior
                var pageUrl = $(this).attr('href'); // Get the href value

                // Load the content into the #content-area div
                $('#content-area').load(pageUrl + ' #content-area > *', function (response, status, xhr) {
                    if (status == "error") {
                        var msg = "Sorry but there was an error: ";
                        $("#content-area").html(msg + xhr.status + " " + xhr.statusText);
                    }
                });
            });
        });
    </script>
</body>

</html>