<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="p-2 md:p-5">

            <div class="dash-alerts alerts"></div>

            <div class="p-5 shadow-sm bg-white">

                <p>
                    Here we can provide summary data for the app, such as listing basic stats about
                    the client applications, a shortlist of applications not yet processed, a
                    shortlist of clients whose accounts are not connected properly, etc.
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
