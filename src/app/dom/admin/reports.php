<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="pt-5 px-5 text-2xl">
            <a href="/admin/reports" class="text-if-hippie-blue hover:text-if-hippie-blue-dark">
                Reports
            </a> &raquo;
            <span class="text-if-shark-500">Download Reports</span>
        </div>

        <div class="mt-2 p-2 md:mt-0 md:p-5">

            <div class="p-5 shadow-sm bg-white">

                <p>
                    This area will be used for generating Excel spreadsheets for client records and
                    other datasets.
                </p>

            </div>

        </div>

    </div>

</div>

<script>
    IF.createNavBar('#nav-bar');
    IF.createTopBar('#top-bar');
</script>

<?php require '_footer.php'; ?>
