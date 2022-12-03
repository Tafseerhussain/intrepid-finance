<template>

    <div class="absolute w-full h-full top-0 left-0 z-20 flex flex-col items-center justify-center">

        <div class="lds-ring-overlay"></div>

        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

    </div>

</template>

<style scoped="scoped">
    .lds-ring-overlay {
        height: 100%;
        width: 100%;
        position: absolute;
        background-color: #fff;
        opacity: 0.7;
        z-index: 20;
    }

    .lds-ring {
        display: inline-block;
        position: relative;
        width: 62px;
        height: 62px;
        z-index: 21;
    }

    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 46px;
        height: 46px;
        margin: 8px;
        border: 8px solid #5ac278;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #5ac278 transparent transparent transparent;
    }

    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }

    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }

    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }

    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
