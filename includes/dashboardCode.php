<?php
if($cUser['role'] == "Admin") {
    // DB connection assumed in $conn

    // Query counts
    $totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
    $totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='student'"))['total'];
    $totalTeachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='teacher'"))['total'];
    // $totalExams = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM exams"))['total'];
    $activeUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='active'"))['total'];
    $inactiveUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='inactive'"))['total'];
?>
<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body p-0">
                <div class="row g-0">

                    <div class="col-sm-6 col-xl-4">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalStudents ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Students</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-user text-muted" style="font-size: 24px;"></i>
                                <h3><span><?= $totalTeachers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Total Teachers</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-document text-muted" style="font-size: 24px;"></i>
                                <h3><span>20</span></h3>
                                <p class="text-muted font-15 mb-0">Total Exams</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-media-play text-success" style="font-size: 24px;"></i>
                                <h3><span><?= $activeUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Active Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4 border-start mt-3">
                        <div class="card shadow-none m-0">
                            <div class="card-body text-center">
                                <i class="dripicons-media-pause text-danger" style="font-size: 24px;"></i>
                                <h3><span><?= $inactiveUsers ?></span></h3>
                                <p class="text-muted font-15 mb-0">Inactive Users</p>
                            </div>
                        </div>
                    </div>

                </div> <!-- end row -->
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>
<?php } ?>
