<?php require '_header.php'; ?>

<div class="flex flex-col items-center justify-center">

    <div class="
        flex items-center justify-between w-full
        bg-white
        lg:max-w-[984px] lg:mt-[20px] lg:rounded-t-lg
        xl:max-w-[1240px] xl:mt-[20px] xl:rounded-t-lg">

        <div class="p-6">
            <img src="/img/logo-with-tags.svg" alt="logo" />
        </div>

        <div class="flex items-center px-5 py-6 text-lg sm:whitespace-nowrap sm:text-xl text-center md:text-2xl md:text-right">
            Growth &amp; Venture Capital Request
        </div>

    </div>

    <div class="flex flex-col items-center w-full" id="app"></div>

</div>

<script type="application/json" id="app-params"></script>

<script>
    IF.createApplication('#app', JSON.parse(document.getElementById('app-params').textContent));
</script>

<?php require '_footer.php'; ?>
