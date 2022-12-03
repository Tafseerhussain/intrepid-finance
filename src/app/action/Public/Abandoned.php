<?php

class Action_Public_Abandoned extends Tell_Action implements Tell_Container_Csrf
{
    protected $user = NULL;

    protected $token = NULL;

    protected $contactBusiness = NULL;

    protected $contactHome = NULL;

    protected $crypto = NULL;

    public function __construct(User $user, Tell_Crypto $crypto)
    {
        $this->user   = $user;
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

    public function result()
    {
        return $this->token;
    }

    public function run()
    {
        $data = [];

        $data['form_type']  = $this->get('form_type');

        $data['form_token'] = $this->token;

        $unit = (new Tell_Bridge_Currency('USD'))->unit();

        if (Tell_Validate::numericMin($this->get('request_amount'), 5000)) {
            $data['request_amount'] = $this->get('request_amount') * $unit;
        }

        if (Tell_Validate::whitelist($this->get('request_type'), array_keys(Lexicon::users_request_type($this->get('form_type'))))) {
            $data['request_type'] = Tell_Json::encode($this->get('request_type'));
        }

        if (Tell_Validate::entity($this->get('company_name'))) {
            $data['company_name'] = $this->get('company_name');
        }

        if (Tell_Validate::firstName($this->get('first_name'))) {
            $data['first_name'] = $this->get('first_name');
        }

        if (Tell_Validate::lastName($this->get('last_name'))) {
            $data['last_name'] = $this->get('last_name');
        }

        if (Tell_Validate::email($this->get('email'))) {
            $data['email'] = $this->get('email');
        }

        if (Tell_Validate::phone($this->get('phone1'))) {
            $data['phone1'] = $this->get('phone1');
        }

        if (Tell_Validate::phone($this->get('phone2'))) {
            $data['phone2'] = $this->get('phone2');
        }

        if (Tell_Validate::dateFormat($this->get('dob'), 'Y-m-d')) {
            $data['dob'] = $this->crypto->encrypt($this->get('dob'));
        }

        if (Tell_Validate::lengthFixed($this->get('ssn'), 4)) {
            $data['ssn'] = $this->crypto->encrypt($this->get('ssn'));
        }

        if (Tell_Validate::digit($this->get('years_in_business'))) {
            $data['years_in_business'] = $this->get('years_in_business');
        }

        if (Tell_Validate::lengthFixed($this->get('tax_id'), 9)) {
            $data['tax_id'] = $this->crypto->encrypt($this->get('tax_id'));
        }

        if ($this->isCommercial()) {
            if (Tell_Validate::numeric($this->get('revenue_annually'))) {
                $data['revenue_annually'] = $this->get('revenue_annually') * $unit;
            }

            if (Tell_Validate::lengthBetween($this->get('previous_financier'), 2, 70)) {
                $data['previous_financier'] = $this->get('previous_financier');
            }
        } elseif ($this->isVenture()) {
            if (Tell_Validate::lengthBetween($this->get('churn_rate'), 2, 70)) {
                $data['churn_rate'] = $this->get('churn_rate');
            }

            if (Tell_Validate::lengthBetween($this->get('previous_financier'), 2, 70)) {
                $data['previous_financier'] = $this->get('previous_financier');
            }

            if (Tell_Validate::numeric($this->get('revenue_monthly'))) {
                $data['revenue_monthly'] = $this->get('revenue_monthly') * $unit;
            }

            if (Tell_Validate::numeric($this->get('money_raised'))) {
                $data['money_raised'] = $this->get('money_raised') * $unit;
            }
        }

        if (Tell_Validate::whitelist($this->get('corp_type'), array_keys(Lexicon::users_corp_type()))) {
            $data['corp_type'] = $this->get('corp_type');
        }

        if (Tell_Validate::whitelist($this->get('credit_score'), array_keys(Lexicon::users_credit_score()))) {
            $data['credit_score'] = $this->get('credit_score');
        }

        $errors = $this->contactBusiness->errors();

        if ( ! isset($errors['business_address1'])) {
            $data['business_address1'] = $this->contactBusiness->address1;
        }

        if ( ! isset($errors['business_address2'])) {
            $data['business_address2'] = $this->contactBusiness->address2;
        }

        if ( ! isset($errors['business_city'])) {
            $data['business_city'] = $this->contactBusiness->city;
        }

        if ( ! isset($errors['business_province'])) {
            $data['business_province'] = $this->contactBusiness->province;
        }

        if ( ! isset($errors['business_postal'])) {
            $data['business_postal'] = $this->contactBusiness->postal;
        }

        if ( ! isset($errors['business_country'])) {
            $data['business_country'] = $this->contactBusiness->country;
        }

        $errors = $this->contactHome->errors();

        if ( ! isset($errors['home_address1'])) {
            $data['home_address1'] = $this->contactHome->address1;
        }

        if ( ! isset($errors['home_address2'])) {
            $data['home_address2'] = $this->contactHome->address2;
        }

        if ( ! isset($errors['home_city'])) {
            $data['home_city'] = $this->contactHome->city;
        }

        if ( ! isset($errors['home_province'])) {
            $data['home_province'] = $this->contactHome->province;
        }

        if ( ! isset($errors['home_postal'])) {
            $data['home_postal'] = $this->contactHome->postal;
        }

        if ( ! isset($errors['home_country'])) {
            $data['home_country'] = $this->contactHome->country;
        }

        if ($this->isCommercial()) {
            if (Tell_Validate::entity($this->get('ref_a_name'))) {
                $data['ref_a_name'] = $this->get('ref_a_name');
            }

            if (Tell_Validate::phone($this->get('ref_a_phone'))) {
                $data['ref_a_phone'] = $this->get('ref_a_phone');
            }

            if (Tell_Validate::numeric($this->get('ref_a_payment'))) {
                $data['ref_a_payment'] = $this->get('ref_a_payment') * $unit;
            }

            if (Tell_Validate::entity($this->get('ref_b_name'))) {
                $data['ref_b_name'] = $this->get('ref_b_name');
            }

            if (Tell_Validate::phone($this->get('ref_b_phone'))) {
                $data['ref_b_phone'] = $this->get('ref_b_phone');
            }

            if (Tell_Validate::numeric($this->get('ref_b_payment'))) {
                $data['ref_b_payment'] = $this->get('ref_b_payment') * $unit;
            }
        } elseif ($this->isVenture()) {
            if (Tell_Validate::entity($this->get('ref_a_name'))) {
                $data['ref_a_name'] = $this->get('ref_a_name');
            }

            if (Tell_Validate::phone($this->get('ref_a_phone'))) {
                $data['ref_a_phone'] = $this->get('ref_a_phone');
            }

            if (Tell_Validate::entity($this->get('ref_b_name'))) {
                $data['ref_b_name'] = $this->get('ref_b_name');
            }

            if (Tell_Validate::phone($this->get('ref_b_phone'))) {
                $data['ref_b_phone'] = $this->get('ref_b_phone');
            }
        }

        $data['last_ip'] = Ip::trusted();

        $data['modified'] = gmdate('Y-m-d H:i:s');

        if ( ! $this->user->isLoaded()) {
            $data['created'] = gmdate('Y-m-d H:i:s');
        }

        $this->user->disableTracker(TRUE);

        $this->user->save($data);

        $this->user->makeReferenceNumber();
    }

    public function verify()
    {
        $this->rules('form_type')->whitelist(array_keys(Lexicon::users_form_type()));

        if ($this->isValid() && ! $this->user->isLoaded()) {
            $this->token = $this->user->getFormToken($this->get('form_token'));

            $this->user->loadByFormToken($this->token);
        }
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
