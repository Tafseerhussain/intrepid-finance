<template>
  <div class="app-extra-payments text-xs mt-5">
    <form>
      <div class="md:flex mb-2">
        <label for="loanDate" class="md:w-1/2 flex items-center"
          >Loan Date?:</label
        >
        <div class="md:w-1/2">
          <input type="date" class="styled w-full" id="loanDate" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="firstPaymentDue" class="md:w-1/2 flex items-center"
          >First Payment Due?:</label
        >
        <div class="md:w-1/2">
          <input type="date" class="styled w-full" id="firstPaymentDue" />
        </div>
      </div>
      <div class="my-5"></div>
      <div class="md:flex mb-2">
        <label for="extraPaymentAmount" class="md:w-1/2 flex items-center"
          >Extra Payment Amount?:</label
        >
        <div class="md:w-1/2">
          <input
            type="number"
            class="styled w-full"
            id="extraPaymentAmount"
            placeholder="0.00"
          />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="extraPaymentStart" class="md:w-1/2 flex items-center"
          >Extra Payments Start?:</label
        >
        <div class="md:w-1/2">
          <input type="date" class="styled w-full" id="extraPaymentStart" />
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="extraPaymentFrequency" class="md:w-1/2 flex items-center"
          >Extra Payment Frequency?:</label
        >
        <div class="md:w-1/2">
          <select id="extraPaymentFrequency" class="styled w-full">
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
        <label for="numberOfExtraPmts" class="md:w-1/2 flex items-center"
          >Number of Extra Pmts? (#):</label
        >
        <div class="md:w-1/2">
          <input type="date" class="styled w-full" id="numberOfExtraPmts" />
        </div>
      </div>
      <div class="text-center italic my-5">
        For "#", type a "U" for "Unknown" to pay until loan is paid off.
      </div>
      <div class="md:flex mb-2">
        <label for="amortizationMethod" class="md:w-1/2 flex items-center"
          >Amortization Method?:</label
        >
        <div class="md:w-1/2">
          <select id="amortizationMethod" class="styled w-full">
            <option value="0" selected="selected">Normal</option>
            <option value="3">Interest Only</option>
          </select>
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="daysPerYear" class="md:w-1/2 flex items-center"
          >Days Per Year?:</label
        >
        <div class="md:w-1/2">
          <select id="daysPerYear" class="styled w-full">
            <option value="0">360</option>
            <option value="1">364</option>
            <option value="2" selected="selected">365</option>
          </select>
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="longPeriodOptions" class="md:w-1/2 flex items-center"
          >Long Period Options?:</label
        >
        <div class="md:w-1/2">
          <select id="longPeriodOptions" class="styled w-full">
            <option value="0">None</option>
            <option value="1" selected="selected">With Origination</option>
            <option value="2">With First</option>
            <option value="3">Amortized</option>
          </select>
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="shortPeriodOptions" class="md:w-1/2 flex items-center"
          >Short Period Options?:</label
        >
        <div class="md:w-1/2">
          <select id="shortPeriodOptions" class="styled w-full">
            <option value="0">No Payment Reduction</option>
            <option value="1" selected="selected">Reduce First</option>
            <option value="2">Reduce All</option>
          </select>
        </div>
      </div>
      <div class="md:flex mb-2">
        <label for="fiscalYearEnd" class="md:w-1/2 flex items-center"
          >Fiscal Year End?:</label
        >
        <div class="md:w-1/2">
          <select id="fiscalYearEnd" class="styled w-full">
            <option value="0">January</option>
            <option value="1">February</option>
            <option value="2">March</option>
            <option value="3">April</option>
            <option value="4">May</option>
            <option value="5">June</option>
            <option value="6">July</option>
            <option value="7">August</option>
            <option value="8">September</option>
            <option value="9">October</option>
            <option value="10">November</option>
            <option value="11" selected="selected">December</option>
          </select>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {};
</script>