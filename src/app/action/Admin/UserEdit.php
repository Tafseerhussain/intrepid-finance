<?php

class Action_Admin_UserEdit extends Tell_Action implements Tell_Container_Csrf
{
    protected $auth = NULL;

    protected $user = NULL;

    protected $note = NULL;

    protected $crypto = NULL;

    protected $id = NULL;

    public function __construct(
        Auth_AdminLogin $auth,
        User            $user,
        UserNote        $note,
        Tell_Crypto     $crypto,
        $id
    ) {
        $this->auth   = $auth;
        $this->user   = $user->setAuth($auth);
        $this->note   = $note;
        $this->crypto = $crypto;
        $this->id     = $id;
    }

    public function run()
    {
        $data = [
            'request_amount'     => NULL,
            'request_type'       => Tell_Json::encode($this->get('request_type')),
            'company_name'       => $this->get('company_name'),
            'first_name'         => $this->get('first_name'),
            'last_name'          => $this->get('last_name'),
            'email'              => $this->get('email'),
            'phone1'             => $this->get('phone1'),
            'phone2'             => $this->get('phone2'),
            'dob'                => NULL,
            'ssn'                => NULL,
            'years_in_business'  => $this->get('years_in_business'),
            'tax_id'             => NULL,
            'revenue_annually'   => NULL,
            'revenue_monthly'    => NULL,
            'churn_rate'         => $this->isVenture() ? $this->get('churn_rate') : NULL,
            'previous_financier' => $this->get('previous_financier'),
            'money_raised'       => NULL,
            'corp_type'          => $this->get('corp_type'),
            'credit_score'       => $this->get('credit_score'),
            'ref_a_name'         => $this->get('ref_a_name'),
            'ref_a_phone'        => $this->get('ref_a_phone'),
            'ref_a_payment'      => NULL,
            'ref_b_name'         => $this->get('ref_b_name'),
            'ref_b_phone'        => $this->get('ref_b_phone'),
            'ref_b_payment'      => NULL,
            'status'             => $this->get('status'),
            'modified'           => gmdate('Y-m-d H:i:s'),
        ];

        if ($this->get('request_amount')) {
            $data['request_amount'] = toCurrencyBase($this->get('request_amount'));
        }

        if ($this->get('dob')) {
            $data['dob'] = $this->crypto->encrypt($this->get('dob'));
        }

        if ($this->get('ssn')) {
            $data['ssn'] = $this->crypto->encrypt($this->get('ssn'));
        }

        if ($this->get('tax_id')) {
            $data['tax_id'] = $this->crypto->encrypt($this->get('tax_id'));
        }

        if ($this->get('revenue_annually')) {
            $data['revenue_annually'] = $this->isCommercial() ? toCurrencyBase($this->get('revenue_annually')) : NULL;
        }

        if ($this->get('revenue_monthly')) {
            $data['revenue_monthly'] = $this->isVenture() ? toCurrencyBase($this->get('revenue_monthly')) : NULL;
        }

        if ($this->get('money_raised')) {
            $data['money_raised'] = $this->isVenture() ? toCurrencyBase($this->get('money_raised')) : NULL;
        }

        if ($this->get('ref_a_payment')) {
            $data['ref_a_payment'] = $this->isCommercial() ? toCurrencyBase($this->get('ref_a_payment')) : NULL;
        }

        if ($this->get('ref_b_payment')) {
            $data['ref_b_payment'] = $this->isCommercial() ? toCurrencyBase($this->get('ref_b_payment')) : NULL;
        }

        if ( ! $this->isAbandoned() && $this->get('password')) {
            $data['password']       = Tell_Password::hash($this->get('password'));
            $data['password_token'] = NULL;
            $data['password_date']  = NULL;
        }

        $this->user->save($data);

        if ($this->get('note')) {
            $this->note->save([
                'user_id'     => $this->id,
                'admin_id'    => $this->auth->get('id'),
                'author_type' => 'Admin',
                'author_name' => $this->auth->get('first_name') . ' ' . $this->auth->get('last_name'),
                'note'        => $this->crypto->encrypt($this->get('note')),
                'ip_address'  => Ip::trusted(),
                'modified'    => gmdate('Y-m-d H:i:s'),
                'created'     => gmdate('Y-m-d H:i:s'),
            ]);
        }
    }

    public function verify()
    {
        if ( ! $this->user->isLoaded() && ! $this->user->load($this->id)) {
            $this->error('id', 'Record not found.');
        }

        $this->rules('status', 'Status')->whitelist(array_keys(Lexicon::users_status()));

        if ($this->isAbandoned()) {
            $this->verifyAbandoned();
        } else {
            $this->verifyApplication();
        }

        if ( ! $this->user->verifyEmailAvailable($this->get('email'))) {
            $this->error('email', 'This email is being used on another account.');
        }

        $this->rules('note')->optional()->lengthBetween(2, 3000);
    }

    protected function verifyApplication()
    {
        $this->rules('request_amount')->required()->numericMin(5000);

        $this->verifyRequestType(TRUE);

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

        if ( ! $this->isAbandoned()) {
            $this->rules('password')->optional()->lengthMin(7);
            $this->rules('password_again')->matches('password');
        }
    }

    protected function verifyAbandoned()
    {
        $this->rules('request_amount')->optional()->numericMin(5000);

        $this->verifyRequestType(FALSE);

        $this->rules('company_name')->required()->entity();
        $this->rules('first_name')->required()->firstName();
        $this->rules('last_name')->required()->lastName();
        $this->rules('email')->required()->email();
        $this->rules('phone1', 'Phone')->optional()->phone();
        $this->rules('phone2', 'Cell')->optional()->phone();
        $this->rules('dob', 'Date of birth')->optional()->dateFormat('Y-m-d');
        $this->rules('ssn', 'SSN')->optional()->digit()->lengthFixed(4);
        $this->rules('years_in_business')->optional()->digit();
        $this->rules('tax_id', 'Tax ID')->optional()->lengthFixed(9);

        if ($this->isCommercial()) {
            $this->rules('revenue_annually', 'Annual revenue')->optional()->numeric();
            $this->rules('previous_financier')->optional()->lengthBetween(2, 70);
        } elseif ($this->isVenture()) {
            $this->rules('churn_rate')->optional()->lengthBetween(2, 70);
            $this->rules('previous_financier')->optional()->lengthBetween(2, 70);
            $this->rules('revenue_monthly', 'Monthly revenue')->optional()->numeric();
            $this->rules('money_raised')->optional()->numeric();
        }

        $this->rules('corp_type', 'Corporation type')->whitelist(array_keys(Lexicon::users_corp_type()));
        $this->rules('credit_score')->whitelist(array_keys(Lexicon::users_credit_score()));

        if ($this->isCommercial()) {
            $this->rules('ref_a_name', 'Reference name')->optional()->entity();
            $this->rules('ref_a_phone', 'Reference phone')->optional()->phone();
            $this->rules('ref_a_payment', 'Reference payment')->optional()->numeric();
            $this->rules('ref_b_name', 'Reference name')->optional()->entity();
            $this->rules('ref_b_phone', 'Reference phone')->optional()->phone();
            $this->rules('ref_b_payment', 'Reference payment')->optional()->numeric();
        } elseif ($this->isVenture()) {
            $this->rules('ref_a_name', 'Reference name')->optional()->entity();
            $this->rules('ref_a_phone', 'Reference phone')->optional()->phone();
            $this->rules('ref_b_name', 'Reference name')->optional()->entity();
            $this->rules('ref_b_phone', 'Reference phone')->optional()->phone();
        }
    }

    protected function verifyRequestType(bool $required)
    {
        $list = Lexicon::users_request_type($this->user->get('form_type'));

        $types = $this->get('request_type');

        if ( ! is_array($types)) {
            $types = [];
        }

        foreach ($types as $type => $value) {
            if ('Y' === $value && isset($list[$type])) {
                return;
            }
        }

        if ($required) {
            $this->error('request_type', 'Please select at least one request type.');
        }
    }

    protected function isAbandoned()
        : bool
    {
        return 'Abandoned' === $this->get('status');
    }

    protected function isCommercial()
        : bool
    {
        return 'commercial_capital' === $this->user->get('form_type');
    }

    protected function isVenture()
        : bool
    {
        return 'venture_capital' === $this->user->get('form_type');
    }
}
