<?php require '_header.php'; ?>

<div id="nav-bar"></div>

<div class="main-container">

    <div id="top-bar"></div>

    <div class="main-content flex-auto">

        <div class="p-2 md:p-5">

            <div class="dash-alerts alerts">

                <div class="alert"><b>Generic.</b> This is a generic alert.</div>

                <div class="alert success mb-5"><b>Success!</b> This is a success alert.</div>

                <div class="alert caution mb-5"><b>Caution!</b> This is a cautionary alert.</div>

                <div class="alert error mb-5"><b>Failure!</b> This is a failure alert.</div>

            </div>

            <div class="p-5 shadow-sm bg-white">

                <h1 class="text-3xl md:text-4xl">The Quick Brown Fox Jumps Over The Lazy Dog...</h1>

                <h2 class="text-2xl md:text-3xl mt-5">The Quick Brown Fox Jumps Over The Lazy Dog...</h2>

                <h3 class="text-xl md:text-2xl mt-5">The Quick Brown Fox Jumps Over The Lazy Dog...</h3>

                <h4 class="text-lg md:text-xl mt-5">The Quick Brown Fox Jumps Over The Lazy Dog...</h4>

                <h5 class="text-base md:text-lg mt-5">The Quick Brown Fox Jumps Over The Lazy Dog...</h5>

            </div>

            <div class="mt-2 p-5 md:mt-5 shadow-sm bg-white">

                <p>
                    Curabitur cursus est odio, ut aliquam quam vehicula eu. Sed ac adipiscing felis,
                    nec ornare leo. Donec non lobortis tellus, ut blandit lectus. Ut ornare pulvinar leo,
                    ut mattis massa sodales at. Donec dui justo, egestas vel pulvinar ac, convallis non
                    urna. Integer pellentesque lacus ut blandit gravida. Donec venenatis, tortor ac
                    sodales convallis, sapien urna tristique nulla, id vulputate sem enim a diam. Aenean
                    dictum condimentum sollicitudin. Maecenas luctus rutrum erat sit amet blandit. Sed
                    porttitor dignissim suscipit. Nunc non felis nunc. Phasellus cursus diam eu nunc
                    viverra, a vestibulum nibh ultrices. Duis iaculis molestie augue, at rutrum lacus
                    volutpat eget.
                </p>

                <ul class="mt-5 ml-3 list-disc list-inside">
                    <li>Fringilla semper eros tincidunt</li>
                    <li>
                        Nulla nec est in sem dictum
                        <ul class="ml-5 list-disc list-inside">
                            <li>Ut aliquam erat</li>
                            <li>Hendrerit felis</li>
                            <li>Amet mattis</li>
                        </ul>
                    </li>
                    <li>fringilla semper eros</li>
                    <li>Pacis Ornatus</li>
                    <li>Humilis De Saeta</li>
                    <li>Cohors</li>
                    <li>Radicitus Lusum</li>
                </ul>

                <p class="mt-5">
                    Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac
                    turpis egestas. Praesent ut varius leo. Nam vitae nisl eu nisl pretium iaculis ut et
                    dolor. Praesent ornare consectetur iaculis. Ut dolor sem, malesuada ac rutrum quis,
                    ultricies sit amet nisl. Quisque eu arcu turpis. Nunc vitae mauris eu massa aliquet
                    aliquam. Praesent sed hendrerit felis, a convallis est. Vivamus gravida nisl non enim
                    posuere, ut aliquam felis cursus. Aenean suscipit, nulla sit amet mattis sollicitudin,
                    mauris arcu sodales velit, at rutrum lorem nulla nec tortor. Pellentesque convallis
                    ultricies ullamcorper. Phasellus fermentum lobortis tortor in tincidunt. Integer ut
                    dignissim nisi. Nullam at elit nec magna interdum tempor porttitor at nibh.
                </p>

                <hr class="mt-5" />

                <p class="mt-5">
                    Suspendisse tincidunt risus in mattis feugiat. Mauris sapien lectus, interdum et
                    bibendum in, eleifend id purus. Morbi porta nibh arcu, vehicula bibendum mauris
                    condimentum vitae. Ut porttitor ipsum aliquam nisi euismod sagittis. Morbi volutpat
                    risus tempus ligula feugiat, nec molestie nulla porttitor. Praesent varius dignissim
                    felis elementum suscipit. In sodales mauris vitae ligula aliquam, nec iaculis nisi
                    convallis. Mauris tristique dolor nec lectus auctor pretium. Suspendisse ut commodo
                    ligula. Integer a nisi molestie quam lobortis imperdiet id ut nulla. Sed feugiat urna
                    quis augue scelerisque, eu dapibus nunc iaculis. Mauris tristique metus ac luctus
                    viverra. Phasellus et ultrices turpis, ac eleifend augue.
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
