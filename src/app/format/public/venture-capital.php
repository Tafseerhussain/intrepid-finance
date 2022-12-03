<?php /** @var Tell_Dom_Html $dom */

$dom('public/venture-capital.php')->apply('public._trait');

$user = $ioc->factory(User::class);
$ip   = new Tell_Ipsum_Contact('US');
$bus  = $ip->contact();
$home = $ip->contact();
$data = [];

$randomPhone = function() {
    return mt_rand(100, 999) . '-' . mt_rand(100, 999) . '-' . mt_rand(1000, 9999);
};

if (Tell::testIp()) {
    $data['request_amount']     = $ip->random(['5000.00', '12500.00', '25000.00', '35000.00', '40000.00', '50000.00']);
    $data['company_name']       = ucwords(implode(' ', $ip->words([2, 3])));
    $data['first_name']         = $ip->firstName();
    $data['last_name']          = $ip->lastName();
    $data['email']              = $ip->email();
    $data['phone1']             = $randomPhone();
    $data['phone2']             = $randomPhone();
    $data['dob']                = mt_rand(1955, 2002) . '-0' . mt_rand(1, 9) . '-' . mt_rand(10, 28);
    $data['ssn']                = mt_rand(1000, 9999);
    $data['years_in_business']  = mt_rand(1, 20);
    $data['tax_id']             = mt_rand(10, 99) . '-' . mt_rand(100000, 999999);
    $data['revenue_annually']   = mt_rand(24, 600) . '000.00';
    $data['revenue_monthly']    = mt_rand(2, 50) . '000.00';
    $data['churn_rate']         = mt_rand(10, 99) . '%';
    $data['previous_financier'] = ucwords(implode(' ', $ip->words([2, 3])));
    $data['money_raised']       = $ip->random(['5000.00', '21000.00', '65000.00']);
    $data['corp_type']          = $ip->random(array_keys(Lexicon::users_corp_type()));
    $data['credit_score']       = $ip->random(array_keys(Lexicon::users_credit_score()));
    $data['business_address1']  = $bus->address1;
    $data['business_address2']  = $bus->address2;
    $data['business_city']      = $bus->city;
    $data['business_province']  = $bus->province;
    $data['business_postal']    = $bus->postal;
    $data['business_country']   = $bus->country;
    $data['home_address1']      = $home->address1;
    $data['home_address2']      = $home->address2;
    $data['home_city']          = $home->city;
    $data['home_province']      = $home->province;
    $data['home_postal']        = $home->postal;
    $data['home_country']       = $home->country;
    $data['ref_a_name']         = $ip->fullName();
    $data['ref_a_phone']        = $randomPhone();
    $data['ref_a_payment']      = mt_rand(1, 50) . '00.00';
    $data['ref_b_name']         = $ip->fullName();
    $data['ref_b_phone']        = $randomPhone();
    $data['ref_b_payment']      = mt_rand(1, 50) . '00.00';
}

$dom('#app-params')->text(Tell_Json::encode([
    'form_type'  => 'venture_capital',
    'form_token' => $user->getFormToken(),
    'data'       => $data,
]));
