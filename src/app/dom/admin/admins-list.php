<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="pt-5 px-5 text-2xl">
            <a href="/admin/admins/list" class="text-if-hippie-blue hover:text-if-hippie-blue-dark">
                Admins
            </a> &raquo;
            <span class="text-if-shark-500">View Admins</span>
        </div>

        <div id="admins-list"></div>

    </div>

</div>

<script>
    IF.createNavBar('#nav-bar');
    IF.createTopBar('#top-bar');
    IF.createAdminsList('#admins-list');
</script>

<?php require '_footer.php'; ?>
