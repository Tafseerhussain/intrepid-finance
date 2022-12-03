<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="pt-5 px-5 text-2xl">
            <a href="/admin/clients/list" class="text-if-hippie-blue hover:text-if-hippie-blue-dark">
                Clients
            </a> &raquo;
            <span class="text-if-shark-500">View Clients</span>
        </div>

        <div id="clients-list"></div>

    </div>

</div>

<script>
    IF.createNavBar('#nav-bar');
    IF.createTopBar('#top-bar');
    IF.createClientsList('#clients-list');
</script>

<?php require '_footer.php'; ?>
