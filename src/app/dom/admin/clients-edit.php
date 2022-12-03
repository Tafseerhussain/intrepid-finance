<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="pt-5 px-5 text-2xl">
            <a href="/admin/clients/list" class="text-if-hippie-blue hover:text-if-hippie-blue-dark">
                Clients
            </a> &raquo;
            <span class="text-if-shark-500">Edit Client</span>
        </div>

        <div class="mt-2 p-2 md:mt-0 md:p-5">

            <div class="dash-alerts alerts"></div>

            <div id="clients-edit">
                <!-- // -->
            </div>

        </div>

    </div>

</div>

<script type="application/json" id="app-params"></script>

<script>
    IF.createNavBar('#nav-bar');
    IF.createTopBar('#top-bar');
    IF.createClientsEdit('#clients-edit', JSON.parse(document.getElementById('app-params').textContent));
</script>

<?php require '_footer.php'; ?>
