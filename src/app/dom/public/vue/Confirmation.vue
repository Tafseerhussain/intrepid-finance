<template>

    <div class="
        w-full bg-white text-if-shark border-t border-silver-300
        lg:max-w-[984px] lg:rounded-b-lg
        xl:max-w-[1240px] xl:rounded-b-lg">

        <div class="flex flex-col md:flex-row p-5">

            <div :class="needsAccount ? 'md:w-1/2 md:pr-2.5' : ''">

                <div class="md:mt-1 text-3xl text-center">
                    Thank You
                </div>

                <div class="mt-5 p-2 md:px-3 md:py-2 rounded-lg leading-7 text-black bg-if-emerald-100">
                    Congratulations, you're on your way to accessing growth capital! We'll be in
                    touch within the next 24 hours.
                </div>

                <div class="mt-4">
                    Ready to jump start things?
                </div>

                <div class="mt-4">
                    Intrepid's underlying technology automatically connects to more than 16,000
                    financial institutions worldwide, and will begin analyzing your information to
                    optimize your growth capital. That's all it takes!
                </div>

            </div>

            <div v-if="needsAccount" class="mt-4 md:mt-0 md:w-1/2 md:pl-2.5">

                <div class="p-3 bg-if-silver-100 border-t border-l border-r border-if-silver-300 text-black font-bold rounded-t-lg">
                    Create your account
                </div>

                <div class="p-2 md:px-5 md:pt-4 md:pb-5 border border-if-silver-300 rounded-b-lg">

                    <div class="flex flex-col">
                        <div class="pb-1">
                            Email Address
                        </div>
                        <input
                            v-model="state.email"
                            :class="inputCss('email')"
                            class="styled disabled w-full cursor-not-allowed"
                            type="text"
                            placeholder="example@domain.com"
                            readonly="readonly"
                        />
                        <div v-if="errors.email" class="field-error">
                            {{ errors.email }}
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row mt-3">
                        <div class="md:w-1/2 md:pr-2.5">
                            <div class="pb-1">
                                Password
                            </div>
                            <input
                                v-model="state.password"
                                :class="inputCss('password')"
                                class="styled w-full"
                                type="password"
                            />
                            <div v-if="errors.password" class="field-error">
                                {{ errors.password }}
                            </div>
                        </div>
                        <div class="mt-3 md:mt-0 md:w-1/2 md:pl-2.5">
                            <div class="pb-1">
                                Password Again
                            </div>
                            <input
                                v-model="state.password_again"
                                class="styled w-full"
                                :class="inputCss('password_again')"
                                type="password"
                            />
                            <div v-if="errors.password_again" class="field-error">
                                {{ errors.password_again }}
                            </div>
                        </div>
                    </div>

                    <button @click="formIntake.submit()" class="
                        w-full mt-4 px-5 py-3 text-xl rounded-lg
                        bg-if-emerald text-white
                        hover:bg-if-emerald-dark
                        md:mt-5 md:w-fit">
                        Create Account
                    </button>

                </div>

            </div>

        </div>

    </div>

    <LoadingIndicator v-if="formIntake.loading" />

</template>

<script>

    import axios from 'axios';
    import { reactive, ref } from 'vue';
    import FormHandler from '@mixins/FormHandler.js';

    class FormIntake extends FormHandler {
        async submit() {
            this.loading = true;
            const action = await axios
                .post('/application/create-account/submit?ajax=1', this.state)
                .then((res) => {
                    window.location.href = '/clients';
                }).catch((error) => {
                    this.loading = false;
                    this.handleErrors(error.response.data.errors);
                });
        }
    };

    export default {
        props : [
            'form_type',
            'form_token',
            'data',
            'needs_account',
        ],
        setup(props) {
            const state = reactive({
                form_type      : props.form_type,
                form_token     : props.form_token,
                email          : props.data.email,
                password       : '',
                password_again : '',
            });

            const errors = reactive({});

            const formIntake = ref(new FormIntake(errors, state));

            const needsAccount = ref(props.needs_account);

            function inputCss(field) {
                return errors[field] ? 'has-error' : '';
            };

            return {
                state,
                errors,
                formIntake,
                needsAccount,
                inputCss,
            };
        },
    };

</script>
