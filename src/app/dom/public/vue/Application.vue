<template>

    <div class="
        w-full bg-white text-if-shark shadow-lg
        lg:max-w-[984px] lg:rounded-b-lg
        xl:max-w-[1240px] xl:rounded-b-lg">

        <div>

            <div class="p-3 bg-if-shark-50 text-black font-bold">
                Capital Request
            </div>

            <div class="p-2 md:px-5 md:pt-4 md:pb-5">

                <div class="flex flex-col xl:flex-row">

                    <div class="md:w-1/2 lg:w-1/3 mt-3 md:mt-0 xl:mr-2.5">
                        <div class="pb-1">
                            Amount Requested
                        </div>
                        <CurrencyInput
                            v-model="state.request_amount"
                            :class="inputCss('request_amount')"
                            class="styled w-full"
                        />
                        <div v-if="errors.request_amount" class="field-error">
                            {{ errors.request_amount }}
                        </div>
                    </div>

                    <div class="w-full mt-5 xl:mt-7 xl:ml-2.5">

                        <div :class="inputCss('request_type')" class="
                            flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                            text-sm xs:text-base bg-if-silver-100 rounded-md">

                            <label v-if="isCommercial()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.equipment"
                                    name="request_type[equipment]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Equipment Financing
                            </label>

                            <label v-if="isCommercial()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.invoice_factoring"
                                    name="request_type[invoice_factoring]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Invoice Factoring
                            </label>

                            <label v-if="isCommercial()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.accounts_receivable"
                                    name="request_type[accounts_receivable]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Accounts Receivable Financing
                            </label>

                            <label v-if="isCommercial()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.lines_of_credit"
                                    name="request_type[lines_of_credit]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Lines of Credit
                            </label>

                            <label v-if="isVenture()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.growth_capital"
                                    name="request_type[growth_capital]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Growth Capital
                            </label>

                            <label v-if="isVenture()" class="px-3 flex">
                                <input
                                    v-model="state.request_type.venture_capital"
                                    name="request_type[venture_capital]"
                                    true-value="Y"
                                    false-value="N"
                                    class="mr-2 styled"
                                    type="checkbox"
                                /> Venture Capital
                            </label>

                        </div>

                        <div v-if="errors.request_type" class="field-error">
                            {{ errors.request_type }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div>

            <div class="p-3 bg-if-shark-50 text-black font-bold">
                Applicant Information
            </div>

            <div class="p-2 md:px-5 md:pt-4 md:pb-5">

                <div>
                    <div class="pb-1">
                        Company Name
                    </div>
                    <input
                        v-model="state.company_name"
                        :class="inputCss('company_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Company Name"
                    />
                    <div v-if="errors.company_name" class="field-error">
                        {{ errors.company_name }}
                    </div>
                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Principal Owner's Name
                        </div>
                        <input
                            v-model="state.first_name"
                            :class="inputCss('first_name')"
                            class="styled w-full"
                            type="text"
                            placeholder="Last Name"
                        />
                        <div v-if="errors.first_name" class="field-error">
                            {{ errors.first_name }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="hidden pb-1 md:block">
                            &nbsp;
                        </div>
                        <input
                            v-model="state.last_name"
                            :class="inputCss('last_name')"
                            class="styled w-full"
                            type="text"
                            placeholder="Last Name"
                        />
                        <div v-if="errors.last_name" class="field-error">
                            {{ errors.last_name }}
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <div class="pb-1">
                        Email Address
                    </div>
                    <input
                        v-model="state.email"
                        :class="inputCss('email')"
                        class="styled w-full"
                        type="text"
                        placeholder="example@domain.com"
                    />
                    <div v-if="errors.email" class="field-error">
                        {{ errors.email }}
                    </div>
                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Phone Number
                        </div>
                        <input
                            v-model="state.phone1"
                            :class="inputCss('phone1')"
                            class="styled w-full"
                            type="text"
                            v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                            placeholder="(###) ###-#### x ###"
                        />
                        <div v-if="errors.phone1" class="field-error">
                            {{ errors.phone1 }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Cell Number
                        </div>
                        <input
                            v-model="state.phone2"
                            :class="inputCss('phone2')"
                            class="styled w-full"
                            type="text"
                            v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                            placeholder="(###) ###-####"
                        />
                        <div v-if="errors.phone2" class="field-error">
                            {{ errors.phone2 }}
                        </div>
                    </div>

                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Date of Birth
                        </div>
                        <input
                            v-model="state.dob"
                            :class="inputCss('dob')"
                            class="styled w-full"
                            type="date"
                        />
                        <div v-if="errors.dob" class="field-error">
                            {{ errors.dob }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Social Security # (Last 4)
                        </div>
                        <input
                            v-model="state.ssn"
                            :class="inputCss('ssn')"
                            class="styled w-full"
                            type="text"
                            v-maska="'####'"
                            placeholder="####"
                        />
                        <div v-if="errors.ssn" class="field-error">
                            {{ errors.ssn }}
                        </div>
                    </div>

                </div>

                <div class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Years in Business
                        </div>
                        <input
                            v-model="state.years_in_business"
                            :class="inputCss('years_in_business')"
                            class="styled w-full"
                            type="text"
                            v-maska="'#*'"
                        />
                        <div v-if="errors.years_in_business" class="field-error">
                            {{ errors.years_in_business }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Tax ID #
                        </div>
                        <input
                            v-model="state.tax_id"
                            :class="inputCss('tax_id')"
                            class="styled w-full"
                            type="text"
                            v-maska="'##-######'"
                            placeholder="##-######"
                        />
                        <div v-if="errors.tax_id" class="field-error">
                            {{ errors.tax_id }}
                        </div>
                    </div>

                </div>

                <div v-if="isCommercial()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Annual Revenue
                        </div>
                        <CurrencyInput
                            v-model="state.revenue_annually"
                            :class="inputCss('revenue_annually')"
                            class="styled w-full"
                        />
                        <div v-if="errors.revenue_annually" class="field-error">
                            {{ errors.revenue_annually }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Previous Financier
                        </div>
                        <input
                            v-model="state.previous_financier"
                            :class="inputCss('previous_financier')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.previous_financier" class="field-error">
                            {{ errors.previous_financier }}
                        </div>
                    </div>

                </div>

                <div v-if="isVenture()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Churn Rate
                        </div>
                        <input
                            v-model="state.churn_rate"
                            :class="inputCss('churn_rate')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.churn_rate" class="field-error">
                            {{ errors.churn_rate }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Previous Financier
                        </div>
                        <input
                            v-model="state.previous_financier"
                            :class="inputCss('previous_financier')"
                            class="styled w-full"
                            type="text"
                        />
                        <div v-if="errors.previous_financier" class="field-error">
                            {{ errors.previous_financier }}
                        </div>
                    </div>

                </div>

                <div v-if="isVenture()" class="md:flex">

                    <div class="mt-3 md:w-1/2 md:mr-2.5">
                        <div class="pb-1">
                            Monthly Recurring Revenue (MRR)
                        </div>
                        <CurrencyInput
                            v-model="state.revenue_monthly"
                            :class="inputCss('revenue_monthly')"
                            class="styled w-full"
                        />
                        <div v-if="errors.revenue_monthly" class="field-error">
                            {{ errors.revenue_monthly }}
                        </div>
                    </div>

                    <div class="mt-3 md:w-1/2 md:ml-2.5">
                        <div class="pb-1">
                            Money Raised
                        </div>
                        <CurrencyInput
                            v-model="state.money_raised"
                            :class="inputCss('money_raised')"
                            class="styled w-full"
                        />
                        <div v-if="errors.money_raised" class="field-error">
                            {{ errors.money_raised }}
                        </div>
                    </div>

                </div>

                <div class="mt-3">

                    <div class="pb-1">
                        Corporation Type
                    </div>

                    <div class="w-full">

                        <div :class="inputCss('corp_type')" class="
                            flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                            text-sm xs:text-base bg-if-silver-100 rounded-md">

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="llc"
                                /> LLC
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="s_corp"
                                /> S Corp
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.corp_type"
                                    name="corp_type"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="c_corp"
                                /> C Corp
                            </label>

                        </div>

                        <div v-if="errors.corp_type" class="field-error">
                            {{ errors.corp_type }}
                        </div>

                    </div>

                </div>

                <div class="mt-3">

                    <div class="pb-1">
                        Credit Score
                    </div>

                    <div class="w-full">

                        <div :class="inputCss('credit_score')" class="
                            flex flex-col xl:flex-row w-full py-3 xl:py-0 xl:h-10 xl:items-center
                            text-sm xs:text-base bg-if-silver-100 rounded-md">

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="720+"
                                /> 720+
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="720-625"
                                /> 720-625
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="625-550"
                                /> 625-550
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="550-"
                                /> 550 and below
                            </label>

                            <label class="px-3 flex">
                                <input
                                    v-model="state.credit_score"
                                    name="credit_score"
                                    class="mr-2 styled"
                                    type="radio"
                                    value="unknown"
                                /> Unknown
                            </label>

                        </div>

                        <div v-if="errors.credit_score" class="field-error">
                            {{ errors.credit_score }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div>

            <div class="p-3 bg-if-shark-50 text-black font-bold">
                Business Address
            </div>

            <div class="p-2 md:px-5 md:pt-4 md:pb-5">

                <div class="flex flex-col">
                    <div class="pb-1">
                        Business Address
                    </div>
                    <input
                        v-model="state.business_address1"
                        :class="inputCss('business_address1')"
                        class="styled"
                        type="text"
                        placeholder="Street address or P.O. box"
                    />
                    <div v-if="errors.business_address1" class="field-error">
                        {{ errors.business_address1 }}
                    </div>
                    <input
                        v-model="state.business_address2"
                        :class="inputCss('business_address2')"
                        class="styled mt-2 md:mt-5"
                        type="text"
                        placeholder="Apt, suite, unit, building, floor, etc."
                    />
                    <div v-if="errors.business_address2" class="field-error">
                        {{ errors.business_address2 }}
                    </div>
                </div>
                <div class="flex flex-col md:flex-row mt-3">
                    <div class="md:w-1/2 md:pr-2.5">
                        <div class="pb-1">
                            Country
                        </div>
                        <country-select
                            v-model="state.business_country"
                            :country="state.business_country"
                            :whiteList="['US']"
                            :removePlaceholder="true"
                            className="styled w-full"
                        />
                        <div v-if="errors.business_country" class="field-error">
                            {{ errors.business_country }}
                        </div>
                    </div>
                    <div class="mt-3 md:mt-0 md:w-1/2 md:pl-2.5">
                        <div class="pb-1">
                            State / Province
                        </div>
                        <region-select
                            v-model="state.business_province"
                            :className="inputCss('business_province') + ' styled w-full'"
                            :country="state.business_country"
                            :region="state.business_province"
                            placeholder="Please Select"
                        />
                        <div v-if="errors.business_province" class="field-error">
                            {{ errors.business_province }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row mt-3">
                    <div class="md:w-1/2 md:pr-2.5">
                        <div class="pb-1">
                            City
                        </div>
                        <input
                            v-model="state.business_city"
                            :class="inputCss('business_city')"
                            class="styled w-full"
                            type="text"
                            placeholder="City"
                        />
                        <div v-if="errors.business_city" class="field-error">
                            {{ errors.business_city }}
                        </div>
                    </div>
                    <div class="mt-3 md:mt-0 md:w-1/2 md:pl-2.5">
                        <div class="pb-1">
                            Postal
                        </div>
                        <input
                            v-model="state.business_postal"
                            :class="inputCss('business_postal')"
                            class="styled w-full"
                            type="text"
                            placeholder="Zip / Postal"
                        />
                        <div v-if="errors.business_postal" class="field-error">
                            {{ errors.business_postal }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div>

            <div class="p-3 bg-if-shark-50 text-black font-bold">
                Home Address
            </div>

            <div class="p-2 md:px-5 md:pt-4 md:pb-5">

                <div class="flex flex-col">
                    <div class="pb-1">
                        Home Address
                    </div>
                    <input
                        v-model="state.home_address1"
                        :class="inputCss('home_address1')"
                        class="styled"
                        type="text"
                        placeholder="Street address or P.O. box"
                    />
                    <div v-if="errors.home_address1" class="field-error">
                        {{ errors.home_address1 }}
                    </div>
                    <input
                        v-model="state.home_address2"
                        :class="inputCss('home_address2')"
                        class="styled mt-2 md:mt-5"
                        type="text"
                        placeholder="Apt, suite, unit, building, floor, etc."
                    />
                    <div v-if="errors.home_address2" class="field-error">
                        {{ errors.home_address2 }}
                    </div>
                </div>
                <div class="flex flex-col md:flex-row mt-3">
                    <div class="md:w-1/2 md:pr-2.5">
                        <div class="pb-1">
                            Country
                        </div>
                        <country-select
                            v-model="state.home_country"
                            :country="state.home_country"
                            :whiteList="['US']"
                            :removePlaceholder="true"
                            className="styled w-full"
                        />
                        <div v-if="errors.home_country" class="field-error">
                            {{ errors.home_country }}
                        </div>
                    </div>
                    <div class="mt-3 md:mt-0 md:w-1/2 md:pl-2.5">
                        <div class="pb-1">
                            State / Province
                        </div>
                        <region-select
                            v-model="state.home_province"
                            :className="inputCss('home_province') + ' styled w-full'"
                            :country="state.home_country"
                            :region="state.home_province"
                            placeholder="Please Select"
                        />
                        <div v-if="errors.home_province" class="field-error">
                            {{ errors.home_province }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row mt-3">
                    <div class="md:w-1/2 md:pr-2.5">
                        <div class="pb-1">
                            City
                        </div>
                        <input
                            v-model="state.home_city"
                            :class="inputCss('home_city')"
                            class="styled w-full"
                            type="text"
                            placeholder="City"
                        />
                        <div v-if="errors.home_city" class="field-error">
                            {{ errors.home_city }}
                        </div>
                    </div>
                    <div class="mt-3 md:mt-0 md:w-1/2 md:pl-2.5">
                        <div class="pb-1">
                            Postal
                        </div>
                        <input
                            v-model="state.home_postal"
                            :class="inputCss('home_postal')"
                            class="styled w-full"
                            type="text"
                            placeholder="Zip / Postal"
                        />
                        <div v-if="errors.home_postal" class="field-error">
                            {{ errors.home_postal }}
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div>

            <div class="p-3 bg-if-shark-50 text-black font-bold">
                References
            </div>

            <div v-if="isCommercial()" class="
                p-2
                md:px-5 md:pt-4 md:pb-0
                lg:flex">
                <div class="lg:w-1/2 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 1
                    </div>
                    <input
                        v-model="state.ref_a_name"
                        :class="inputCss('ref_a_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 1 Name"
                    />
                    <div v-if="errors.ref_a_name" class="field-error">
                        {{ errors.ref_a_name }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:px-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_a_phone"
                        :class="inputCss('ref_a_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_a_phone" class="field-error">
                        {{ errors.ref_a_phone }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Monthly Payment
                    </div>
                    <CurrencyInput
                        v-model="state.ref_a_payment"
                        :class="inputCss('ref_a_payment')"
                        class="styled w-full"
                    />
                    <div v-if="errors.ref_a_payment" class="field-error">
                        {{ errors.ref_a_payment }}
                    </div>
                </div>
            </div>

            <div v-if="isCommercial()" class="
                p-2 border-t border-if-silver-500
                md:mt-5 md:px-5 md:pt-4 md:pb-5
                lg:flex lg:mt-0 lg:border-none">
                <div class="lg:w-1/2 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 2
                    </div>
                    <input
                        v-model="state.ref_b_name"
                        :class="inputCss('ref_b_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 2 Name"
                    />
                    <div v-if="errors.ref_b_name" class="field-error">
                        {{ errors.ref_b_name }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:px-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_b_phone"
                        :class="inputCss('ref_b_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_b_phone" class="field-error">
                        {{ errors.ref_b_phone }}
                    </div>
                </div>
                <div class="lg:w-1/4 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Monthly Payment
                    </div>
                    <CurrencyInput
                        v-model="state.ref_b_payment"
                        :class="inputCss('ref_b_payment')"
                        class="styled w-full"
                    />
                    <div v-if="errors.ref_b_payment" class="field-error">
                        {{ errors.ref_b_payment }}
                    </div>
                </div>
            </div>

            <div v-if="isVenture()" class="
                p-2
                md:px-5 md:pt-4 md:pb-0
                lg:flex">
                <div class="lg:w-2/3 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 1
                    </div>
                    <input
                        v-model="state.ref_a_name"
                        :class="inputCss('ref_a_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 1 Name"
                    />
                    <div v-if="errors.ref_a_name" class="field-error">
                        {{ errors.ref_a_name }}
                    </div>
                </div>
                <div class="lg:w-1/3 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_a_phone"
                        :class="inputCss('ref_a_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_a_phone" class="field-error">
                        {{ errors.ref_a_phone }}
                    </div>
                </div>
            </div>

            <div v-if="isVenture()" class="
                p-2 border-t border-if-silver-500
                md:mt-5 md:px-5 md:pt-4 md:pb-5
                lg:flex lg:mt-0 lg:border-none">
                <div class="lg:w-2/3 lg:pr-2.5">
                    <div class="pb-1">
                        Reference 2
                    </div>
                    <input
                        v-model="state.ref_b_name"
                        :class="inputCss('ref_b_name')"
                        class="styled w-full"
                        type="text"
                        placeholder="Reference 2 Name"
                    />
                    <div v-if="errors.ref_b_name" class="field-error">
                        {{ errors.ref_b_name }}
                    </div>
                </div>
                <div class="lg:w-1/3 mt-3 lg:mt-0 lg:pl-2.5">
                    <div class="pb-1">
                        Phone Number
                    </div>
                    <input
                        v-model="state.ref_b_phone"
                        :class="inputCss('ref_b_phone')"
                        class="styled w-full"
                        type="text"
                        v-maska="['(###) ###-####', '(###) ###-#### x #####']"
                        placeholder="(###) ###-#### x ###"
                    />
                    <div v-if="errors.ref_b_phone" class="field-error">
                        {{ errors.ref_b_phone }}
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="
        w-full p-2
        border-t border-if-silver-500 bg-white text-if-shark
        md:p-5
        lg:max-w-[984px] lg:mt-5 lg:p-0 lg:border-none lg:bg-transparent
        xl:max-w-[1240px]">

        <div :class="inputCss('tos_agree')" class="tos-box flex items-start rounded-lg">
            <input
                v-model="state.tos_agree"
                class="styled"
                type="checkbox"
                true-value="Y"
                false-value="N"
            />
            <div class="ml-3 leading-none">
                I have read and agree to the
                <a
                    @click.prevent.stop="modalTos.open()"
                    class="text-if-shark underline hover:text-if-emerald hover:no-underline"
                    href="!#">terms of service</a>.
                <div v-if="errors.tos_agree" class="field-error w-full">
                    {{ errors.tos_agree }}
                </div>
            </div>
        </div>

        <div :class="inputCss('tos_certify')" class="tos-box flex items-start mt-5 rounded-lg">
            <input
                v-model="state.tos_certify"
                class="mt-1 styled"
                type="checkbox"
                true-value="Y"
                false-value="N"
            />
            <div class="ml-3 leading-snug">
                By submitting this application, I/we certify that all information herein is true and
                complete. I authorize Intrepid Finance &amp; Venture Co. LLC, to retain this
                application, to rely on the foregoing, to check and verify my credit history, my
                employment, business, background and salary/revenue information or any other
                information required to fulfill the request for capital and to share this
                information as outlined in our
                <a
                    @click.prevent.stop="modalTos.open()"
                    class="text-if-shark underline hover:text-if-emerald hover:no-underline"
                    href="!#">terms of service</a>.
                I further have read, understand, and agree to all the terms and conditions, privacy
                policy and hold harmless agreements herein disclosed therein. Intrepid Finance &amp;
                Venture Co. LLC, does not offer or give any legal or tax advice, please consult your
                attorney, accountant or tax advisor with any questions. I/we agree to this entire
                agreement set forth.
                <div v-if="errors.tos_certify" class="field-error w-full">
                    {{ errors.tos_certify }}
                </div>
            </div>
        </div>

    </div>

    <div class="
        w-full p-2
        border-t border-if-silver-500 bg-white
        md:p-5
        lg:max-w-[984px] lg:mt-5 lg:mb-10 lg:p-0 lg:border-none lg:bg-transparent
        xl:max-w-[1240px]">

        <button @click="formIntake.submit()" class="
            w-full px-8 py-5
            bg-if-emerald text-white
            hover:bg-if-emerald-dark
            text-xl rounded-lg
            md:w-fit
            lg:m-0">
            Send Request
        </button>

    </div>

    <LoadingIndicator v-if="formIntake.loading" />

    <WidgetModal
        @close="modalTos.close()"
        :active="modalTos.active"
        title="Privacy, Terms & Conditions">
        <template #content>
            <TosCommercialCapital v-if="state.form_type === 'commercial_capital'" />
            <TosVentureCapital v-if="state.form_type === 'venture_capital'" />
        </template>
        <template #actions>
            <div class="flex w-full justify-end">
                <button @click="modalTos.close()" class="generic h-10 px-3">
                    Close
                </button>
            </div>
        </template>
    </WidgetModal>

</template>

<style scoped="scoped">

    .has-error {
        @apply
            border-red-600
            border-2;
    }

    .tos-box.has-error {
        @apply
            p-3;
    }

</style>

<script>

    import axios from 'axios';
    import { reactive, ref, watch } from 'vue';
    import { useDeviceStore } from '@stores/Device.js';
    import FormHandler from '@mixins/FormHandler.js';
    import LoadingIndicator from '@widgets/LoadingSpinner.vue';
    import WidgetModal from '@widgets/Modal.vue';
    import CurrencyInput from '@widgets/CurrencyInput.vue';
    import TosCommercialCapital from '@/TosCommercialCapital.vue';
    import TosVentureCapital from '@/TosVentureCapital.vue';

    class FormIntake extends FormHandler {
        constructor(errors, state) {
            super(errors, state);
            this.timeout = null;
            this.freeze  = false;
        }

        async abandon() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            if (this.freeze) {
                this.freeze = false;
                return;
            }

            this.timeout = setTimeout(async () => {
                const action = await axios
                    .post('/application/abandoned?ajax=1', this.state)
                    .catch((error) => {
                        // do nothing
                    }).then((res) => {
                        if (this.state.form_token !== res.data) {
                            this.freeze           = true;
                            this.state.form_token = res.data;
                        }
                    });
            }, 5000);
        }

        async submit() {
            this.loading = true;

            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            const action = await axios
                .post('/application/submit?ajax=1', this.state)
                .then((res) => {
                    window.location.href = '/application/confirmation/' + res.data;
                }).catch((error) => {
                    this.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    class ModalTos {
        constructor() {
            this.active = false;
        }

        open() {
            this.active = true;
        }

        close() {
            this.active = false;
        }
    };

    export default {
        props : [
            'form_type',
            'form_token',
            'data',
        ],
        components : {
            WidgetModal,
            LoadingIndicator,
            CurrencyInput,
            TosCommercialCapital,
            TosVentureCapital,
        },
        setup(props) {
            const state = reactive({
                form_type      : props.form_type,
                form_token     : props.form_token,
                request_amount : props.data.request_amount ?? null,
                request_type   : {
                    equipment           : 'N',
                    invoice_factoring   : 'N',
                    accounts_receivable : 'N',
                    lines_of_credit     : 'N',
                    growth_capital      : 'N',
                    venture_capital     : 'N',
                },
                company_name       : props.data.company_name ?? null,
                first_name         : props.data.first_name ?? null,
                last_name          : props.data.last_name ?? null,
                email              : props.data.email ?? null,
                phone1             : props.data.phone1 ?? null,
                phone2             : props.data.phone2 ?? null,
                dob                : props.data.dob ?? null,
                ssn                : props.data.ssn ?? null,
                years_in_business  : props.data.years_in_business ?? null,
                tax_id             : props.data.tax_id ?? null,
                revenue_annually   : props.data.revenue_annually ?? null,
                revenue_monthly    : props.data.revenue_monthly ?? null,
                churn_rate         : props.data.churn_rate ?? null,
                previous_financier : props.data.previous_financier ?? null,
                money_raised       : props.data.money_raised ?? null,
                corp_type          : props.data.corp_type ?? null,
                credit_score       : props.data.credit_score ?? null,
                business_address1  : props.data.business_address1 ?? null,
                business_address2  : props.data.business_address2 ?? null,
                business_city      : props.data.business_city ?? null,
                business_province  : props.data.business_province ?? null,
                business_postal    : props.data.business_postal ?? null,
                business_country   : props.data.business_country ?? 'US',
                home_address1      : props.data.home_address1 ?? null,
                home_address2      : props.data.home_address2 ?? null,
                home_city          : props.data.home_city ?? null,
                home_province      : props.data.home_province ?? null,
                home_postal        : props.data.home_postal ?? null,
                home_country       : props.data.home_country ?? 'US',
                ref_a_name         : props.data.ref_a_name ?? null,
                ref_a_phone        : props.data.ref_a_phone ?? null,
                ref_a_payment      : props.data.ref_a_payment ?? null,
                ref_b_name         : props.data.ref_b_name ?? null,
                ref_b_phone        : props.data.ref_b_phone ?? null,
                ref_b_payment      : props.data.ref_b_payment ?? null,
                tos_agree          : 'N',
                tos_certify        : 'N',
            });

            const errors = reactive({});

            const formIntake = ref(new FormIntake(errors, state));

            const modalTos = ref(new ModalTos());

            const device = useDeviceStore();

            function inputCss(field) {
                return errors[field] ? 'has-error' : '';
            };

            function isCommercial() {
                return 'commercial_capital' === state.form_type;
            };

            function isVenture() {
                return 'venture_capital' === state.form_type;
            };

            watch(() => state, (val, prevVal) => {
                formIntake.value.abandon();
            }, { deep : true });

            return {
                state,
                errors,
                formIntake,
                modalTos,
                device,
                inputCss,
                isCommercial,
                isVenture,
            };
        },
    };

</script>
