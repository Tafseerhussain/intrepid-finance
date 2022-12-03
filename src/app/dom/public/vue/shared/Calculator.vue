<template>
  <div class="app-calculator text-xs mt-5">
    <form>
      <div class="md:flex mb-2">
        <label for="amountLoan" class="md:w-1/2 flex items-center"
          >Amount of Loan?:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="amountLoan" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="annualRate" class="md:w-1/2 flex items-center"
          >Annual Rate?:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="annualRate" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="balloon" class="md:w-1/2 flex items-center"
          >Balloon Due at Payment? (#):</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="balloon" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="periodic" class="md:w-1/2 flex items-center"
          >Periodic Payment?:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="periodic" />
        </div>
      </div>
      <div class="my-5 row justify-content-md-center text-center italic">
        Optionally enter a "0" (zero) for one unknown value above.
      </div>
      <div class="md:flex mb-2">
        <label for="finalBallon" class="md:w-1/2 flex items-center"
          >Final/Balloon Payment (can be 0)?:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="finalBallon" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="annualRate" class="md:w-1/2 flex items-center"
          >Payment Frequency?:</label
        >
        <div class="md:w-1/2">
          <select class="styled w-full">
            <option value="1">Daily</option>
            <option value="2">Weekly</option>
            <option value="3">BiWeekly</option>
            <option value="4">Twice Monthly</option>
            <option value="5">Every 4 Weeks</option>
            <option value="6" selected="selected">Monthly</option>
            <option value="7">BiMonthly</option>
            <option value="8">Quarterly</option>
            <option value="9">Every 4 Months</option>
            <option value="10">Semiannually</option>
            <option value="11">Annually</option>
          </select>
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="annualRate" class="md:w-1/2 flex items-center"
          >Compounding?:</label
        >
        <div class="md:w-1/2">
          <select class="styled w-full">
            <option value="0">Continuous</option>
            <option value="1">Daily</option>
            <option value="2">Weekly</option>
            <option value="3">BiWeekly</option>
            <option value="4">Twice Monthly</option>
            <option value="5">Every 4 Weeks</option>
            <option value="6" selected="selected">Monthly</option>
            <option value="7">BiMonthly</option>
            <option value="8">Quarterly</option>
            <option value="9">Every 4 Months</option>
            <option value="10">Semiannually</option>
            <option value="11">Annually</option>
            <option value="12">Exact/Simple</option>
          </select>
        </div>
      </div>
      <div class="my-8"></div>
      <div class="md:flex mb-2">
        <label for="totalInterest" class="md:w-1/2 flex items-center"
          >Total Interest Paid:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="amountLoan" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="totalPrincipal" class="md:w-1/2 flex items-center"
          >Total Principal & Interest:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="totalPrincipal" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="extrapayment" class="md:w-1/2 flex items-center"
          >Extra Payments Total:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="extrapayment" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="interestsave" class="md:w-1/2 flex items-center"
          >Interest Saved - Due to Extra Pmts:</label
        >
        <div class="md:w-1/2">
          <input type="number" class="styled w-full" id="interestsave" />
        </div>
      </div>
      <div class="flex items-center mb-2 justify-between mt-5">
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Calc
        </button>
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Clear
        </button>
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Print
        </button>
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Help
        </button>
      </div>
      <div class="flex items-center mb-2 justify-between">
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Payment Schedule
        </button>
        <button
          type="button"
          class="
            w-full
            px-5
            py-2
            bg-if-emerald
            text-white
            hover:bg-if-emerald-dark
            text-xs
            rounded-lg
            mr-2
          "
        >
          Charts
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: "Calculator",
};
</script>