<?php

class Action_Public_Application extends Tell_Action implements Tell_Container_Csrf
{
    public $user = NULL;

    public $token = NULL;

    public $contactBusiness = NULL;

    public $contactHome = NULL;

    protected $crypto = NULL;

    public function __construct(User $user, Tell_Crypto $crypto)
    {
        $this->user = $user;

        $this->crypto = $crypto;

        $this->contactBusiness = (new Tell_Bridge_Contact('business'))
            ->address1($this->get('business_address1'))
            ->address2($this->get('business_address2'))
            ->city($this->get('business_city'))
            ->province($this->get('business_province'))
            ->postal($this->get('business_postal'))
            ->country($this->get('business_country'));

        $this->contactHome = (new Tell_Bridge_Contact('home'))
            ->address1($this->get('home_address1'))
            ->address2($this->get('home_address2'))
            ->city($this->get('home_city'))
            ->province($this->get('home_province'))
            ->postal($this->get('home_postal'))
            ->country($this->get('home_country'));
    }

    public function run()
    {
        $unit = (new Tell_Bridge_Currency('USD'))->unit();

        $data = [
            'form_type'          => $this->get('form_type'),
            'form_token'         => $this->token,
            'form_verified'      => 'Y',
            'request_amount'     => $this->get('request_amount') * $unit,
            'request_type'       => Tell_Json::encode($this->get('request_type')),
            'company_name'       => $this->get('company_name'),
            'first_name'         => $this->get('first_name'),
            'last_name'          => $this->get('last_name'),
            'email'              => $this->get('email'),
            'phone1'             => $this->get('phone1'),
            'phone2'             => $this->get('phone2'),
            'dob'                => $this->crypto->encrypt($this->get('dob')),
            'ssn'                => $this->crypto->encrypt($this->get('ssn')),
            'years_in_business'  => $this->get('years_in_business'),
            'tax_id'             => $this->crypto->encrypt($this->get('tax_id')),
            'revenue_annually'   => $this->isCommercial() ? $this->get('revenue_annually') * $unit : NULL,
            'revenue_monthly'    => $this->isVenture() ? $this->get('revenue_monthly') * $unit : NULL,
            'churn_rate'         => $this->isVenture() ? $this->get('churn_rate') : NULL,
            'previous_financier' => $this->get('previous_financier'),
            'money_raised'       => $this->isVenture() ? $this->get('money_raised') * $unit : NULL,
            'corp_type'          => $this->get('corp_type'),
            'credit_score'       => $this->get('credit_score'),
            'business_address1'  => $this->get('business_address1'),
            'business_address2'  => $this->get('business_address2'),
            'business_city'      => $this->get('business_city'),
            'business_province'  => $this->get('business_province'),
            'business_postal'    => $this->get('business_postal'),
            'business_country'   => $this->get('business_country'),
            'home_address1'      => $this->get('home_address1'),
            'home_address2'      => $this->get('home_address2'),
            'home_city'          => $this->get('home_city'),
            'home_province'      => $this->get('home_province'),
            'home_postal'        => $this->get('home_postal'),
            'home_country'       => $this->get('home_country'),
            'ref_a_name'         => $this->get('ref_a_name'),
            'ref_a_phone'        => $this->get('ref_a_phone'),
            'ref_a_payment'      => $this->isCommercial() ? $this->get('ref_a_payment') * $unit : NULL,
            'ref_b_name'         => $this->get('ref_b_name'),
            'ref_b_phone'        => $this->get('ref_b_phone'),
            'ref_b_payment'      => $this->isCommercial() ? $this->get('ref_b_payment') * $unit : NULL,
            'password'           => NULL,
            'password_token'     => NULL,
            'password_date'      => NULL,
            'status'             => 'Prospect',
            'last_ip'            => Ip::trusted(),
            'modified'           => gmdate('Y-m-d H:i:s'),
            'deleted'            => NULL,
        ];

        if ( ! $this->user->isLoaded()) {
            $data['created'] = gmdate('Y-m-d H:i:s');
        }

        $this->user->disableTracker(TRUE);

        $this->user->save($data);

        $this->user->clearFormToken();

        $this->user->makeReferenceNumber();

        try {
            $this->user->sendConfirmationEmail();
        } catch (Throwable $e) {
            Tell_Exception_Handle::exception($e);
        }
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded()) {
            $this->token = $this->user->getFormToken($this->get('form_token'));

            $this->user->loadByFormToken($this->token);
        }

        if ( ! $this->token) {
            $this->token = $this->user->getFormToken();
        }

        $this->rules('form_type')->whitelist(array_keys(Lexicon::users_form_type()));
        $this->rules('request_amount')->required()->numericMin(5000);

        $this->verifyRequestType();

        $this->rules('company_name')->required()->entity();
        $this->rules('first_name')->required()->firstName();
        $this->rules('last_name')->required()->lastName();
        $this->rules('email')->required()->email();
        $this->rules('phone1', 'Phone')->required()->phone();
        $this->rules('phone2', 'Cell')->required()->phone();
        $this->rules('dob', 'Date of birth')->required()->dateFormat('Y-m-d');
        $this->rules('ssn', 'SSN')->required()->digit()->lengthFixed(4);
        $this->rules('years_in_business')->required()->digit();
        $this->rules('tax_id', 'Tax ID')->required()->lengthFixed(9);

        if ($this->isCommercial()) {
            $this->rules('revenue_annually', 'Annual revenue')->required()->numeric();
            $this->rules('previous_financier')->optional()->lengthBetween(2, 70);
        } elseif ($this->isVenture()) {
            $this->rules('churn_rate')->required()->lengthBetween(2, 70);
            $this->rules('previous_financier')->optional()->lengthBetween(2, 70);
            $this->rules('revenue_monthly', 'Monthly revenue')->required()->numeric();
            $this->rules('money_raised')->required()->numeric();
        }

        $this->rules('corp_type', 'Corporation type')->whitelist(array_keys(Lexicon::users_corp_type()));
        $this->rules('credit_score')->whitelist(array_keys(Lexicon::users_credit_score()));

        $this->errors($this->contactBusiness->errors());
        $this->errors($this->contactHome->errors());

        if ($this->isCommercial()) {
            $this->rules('ref_a_name', 'Reference name')->required()->entity();
            $this->rules('ref_a_phone', 'Reference phone')->required()->phone();
            $this->rules('ref_a_payment', 'Reference payment')->required()->numeric();
            $this->rules('ref_b_name', 'Reference name')->required()->entity();
            $this->rules('ref_b_phone', 'Reference phone')->required()->phone();
            $this->rules('ref_b_payment', 'Reference payment')->required()->numeric();
        } elseif ($this->isVenture()) {
            $this->rules('ref_a_name', 'Reference name')->required()->entity();
            $this->rules('ref_a_phone', 'Reference phone')->required()->phone();
            $this->rules('ref_b_name', 'Reference name')->required()->entity();
            $this->rules('ref_b_phone', 'Reference phone')->required()->phone();
        }

        if ( ! $this->user->verifyEmailAvailable($this->get('email'))) {
            $this->error('email', 'This email is being used on another account.');
        }

        if ('Y' !== $this->get('tos_agree')) {
            $this->error('tos_agree', 'You must agree to the terms of service.');
        }

        if ('Y' !== $this->get('tos_certify')) {
            $this->error('tos_certify', 'You must certify and authorize this form submission.');
        }
    }

    protected function verifyRequestType()
    {
        $list = Lexicon::users_request_type($this->get('form_type'));

        $types = $this->get('request_type');

        if ( ! is_array($types)) {
            $types = [];
        }

        foreach ($types as $type => $value) {
            if ('Y' === $value && isset($list[$type])) {
                return;
            }
        }

        $this->error('request_type', 'Please select at least one request type.');
    }

    protected function isCommercial()
        : bool
    {
        return 'commercial_capital' === $this->get('form_type');
    }

    protected function isVenture()
        : bool
    {
        return 'venture_capital' === $this->get('form_type');
    }
}
