<template>
  <div class="app my-10">
    <div class="container">
      <div class="row justify-content-md-center wrapper-calculator">
        <div
          class="
            flex flex-wrap
            text-sm
            text-center
            border-b border-gray-200
          "
        >
          <button
            class="inline-block px-4 pb-2 rounded-t-lg"
            @click="tab = 'calculator'"
            :class="{ 'active-tab': tab === 'calculator' }"
          >
            Calculator
          </button>
          <button
            @click="tab = 'extra-payments'"
            class="inline-block px-4 pb-2 rounded-t-lg"
            :class="{ 'active-tab': tab === 'extra-payments' }"
          >
            Set Dates or Extra Payments
          </button>
        </div>
        <div class="w-full">
          <div class="tab-pane fade" id="home" v-if="tab === 'calculator'">
            <calculator />
          </div>
          <div class="tab-pane fade" v-if="tab === 'extra-payments'">
            <extra-payments />
          </div>
        </div>
        <div class="flex justify-between mt-5 mb-5">
          <div class="small">
            ©2022 Pine Grove Software LLC, all rights reserved
          </div>
          <div class="small">$ : DD/MM/YYYY</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Calculator from "./shared/Calculator.vue";
import ExtraPayments from "./shared/ExtraPayments.vue";

export default {
  components: {
    Calculator,
    ExtraPayments,
  },
  data() {
    return {
      tab: "calculator",
    };
  },
};
</script>

<style scoped>
.wrapper-calculator {
  background-color: #f5f5f5;
  border-radius: 0.3rem;
  border: 1px solid #dedede;
  box-shadow: 2px 2px #f5f5f5;
  padding: 5px 25px;
}

.active-tab {
  text-decoration: none;
  background-color: #fff;
  border-color: #dedede #dedede #1c9663;
  border-bottom-width: 2px;
}

.small {
    font-size: 9px;
}
</style>