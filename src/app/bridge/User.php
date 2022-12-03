<?php

class User extends Record
{
    use Trait_CurrencyFields;
    use Trait_JsonFields;

    const TABLE   = 'users';
    const WHERE   = 'id = ? AND deleted IS NULL';
    const PRIMARY = 'id';

    protected static $currencyFields = [
        'request_amount',
        'revenue_annually',
        'revenue_monthly',
        'money_raised',
        'ref_a_payment',
        'ref_b_payment',
    ];

    protected static $jsonFields = [
        'request_type',
    ];

    protected static $trackableFields = [
        'request_amount'     => 'Request Amount',
        'request_type'       => 'Request Type',
        'company_name'       => 'Company Name',
        'first_name'         => 'Owner\'s First Name',
        'last_name'          => 'Owner\'s Last Name',
        'email'              => 'Email',
        'phone1'             => 'Phone 1',
        'phone2'             => 'Phone 2',
        'dob'                => 'DoB',
        'ssn'                => 'SSN',
        'years_in_business'  => 'Years in Business',
        'tax_id'             => 'Tax ID',
        'revenue_annually'   => 'Annual Revenue',
        'revenue_monthly'    => 'Monthly Revenue',
        'churn_rate'         => 'Churn Rate',
        'previous_financier' => 'Previous Financier',
        'money_raised'       => 'Money Raised',
        'corp_type'          => 'Corp Type',
        'credit_score'       => 'Credit Score',
        'business_address1'  => 'Business Address 1',
        'business_address2'  => 'Business Address 2',
        'business_city'      => 'Business City',
        'business_province'  => 'Business Province',
        'business_postal'    => 'Business Postal',
        'business_country'   => 'Business Country',
        'home_address1'      => 'Home Address 1',
        'home_address2'      => 'Home Address 2',
        'home_city'          => 'Home City',
        'home_province'      => 'Home Province',
        'home_postal'        => 'Home Postal',
        'home_country'       => 'Home Country',
        'ref_a_name'         => 'Ref 1 Name',
        'ref_a_phone'        => 'Ref 1 Phone',
        'ref_a_payment'      => 'Ref 1 Payment',
        'ref_b_name'         => 'Ref 2 Name',
        'ref_b_phone'        => 'Ref 2 Phone',
        'ref_b_payment'      => 'Ref 2 Payment',
        'status'             => 'Status',
        'mx_user_guid'       => 'MX User GUID',
        'mx_member_guid'     => 'MX Member GUID',
    ];

    protected static $safeFields = [
        'form_type',
        'form_token',
        'form_verified',
        'reference_number',
        'request_amount',
        'request_type',
        'company_name',
        'first_name',
        'last_name',
        'email',
        'phone1',
        'phone2',
        'dob',
        'ssn',
        'years_in_business',
        'tax_id',
        'revenue_annually',
        'revenue_monthly',
        'churn_rate',
        'previous_financier',
        'money_raised',
        'corp_type',
        'credit_score',
        'business_address1',
        'business_address2',
        'business_city',
        'business_province',
        'business_postal',
        'business_country',
        'home_address1',
        'home_address2',
        'home_city',
        'home_province',
        'home_postal',
        'home_country',
        'ref_a_name',
        'ref_a_phone',
        'ref_a_payment',
        'ref_b_name',
        'ref_b_phone',
        'ref_b_payment',
        'status',
        'needs_account',
        'mx_needs_widget',
    ];

    /**
     * Instance of Tell_Auth_Login.
     * ---
     * @var  Tell_Auth_Login
     */
    protected $auth = NULL;

    /**
     * Instance of Tracker.
     * ---
     * @var  Tracker
     */
    protected $tracker = NULL;

    /**
     * An array of messages, describing actions taken by the author.
     * ---
     * @var  array
     */
    protected $actions = [];

    protected $disableTracker = FALSE;

    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    public function setAuth(Tell_Auth_Login $auth)
        : self
    {
        $this->auth = $auth;

        return $this;
    }

    public function disableTracker(bool $disable = TRUE)
        : self
    {
        $this->disableTracker = $disable;

        return $this;
    }

    public function getSafeFields()
        : array
    {
        $fields = $this->isLoaded() ? self::$safeFields : [];
        $data   = [];

        foreach ($fields as $field) {
            $data[$field] = $this->get($field);
        }

        return $data;
    }

    public function loadByFormToken(string $formToken, bool $instance = FALSE)
    {
        $sql = 'form_token = ? AND deleted IS NULL';

        return $this->where($sql, $formToken, $instance);
    }

    public function getFormToken(?string $token = NULL, bool $forceNew = FALSE)
        : string
    {
        if ( ! $token) {
            $token = Tell_Session::get('form_token');
        }

        if ( ! $token || $forceNew) {
            $token = sha1(Tell_Security::random(32));
        }

        if ( ! $forceNew && ! $this->isFormTokenValid($token)) {
            $token = $this->getFormToken(NULL, TRUE);
        }

        Tell_Session::set('form_token', $token);

        return $token;
    }

    public function clearFormToken()
        : void
    {
        Tell_Session::delete('form_token');
    }

    public function isFormTokenValid(string $formToken)
        : bool
    {
        if (40 !== strlen($formToken)) {
            return FALSE;
        }

        $sql = '
            GRAB status
            FROM users
            WHERE form_token = ?
            AND deleted IS NULL
        ';

        $status = $this->db
            ->query($sql)
            ->bind($formToken)
            ->run();

        return $status && 'Abandoned' === $status;
    }

    public function verifyEmailAvailable(?string $email)
        : bool
    {
        if ($this->isLoaded()) {
            $sql = "
                GRAB id
                FROM users
                WHERE id != ?
                AND email = ?
                AND status != 'Abandoned'
                AND deleted IS NULL
            ";

            $row = $this->db->query($sql)
                ->bind($this->get('id'), $email)
                ->run();
        } else {
            $sql = "
                GRAB id
                FROM users
                WHERE email = ?
                AND status != 'Abandoned'
                AND deleted IS NULL
            ";

            $row = $this->db->query($sql)
                ->bind($email)
                ->run();
        }

        return ! $row;
    }

    public function isUsableAccount()
        : bool
    {
        return $this->isLoaded() && 'Abandoned' !== $this->get('status');
    }

    public function needsAccountCreated()
        : bool
    {
        return $this->isLoaded()
            && 'Abandoned' !== $this->get('status')
            && NULL === $this->get('password');
    }

    public function sendConfirmationEmail()
    {
        if ( ! $this->isLoaded()) {
            throw new Tell_Exception('Unable to send email. Record not loaded.');
        }

        (new Tell_Email())->to($this->get('email'))
            ->format('emails/confirmation', $this->get())
            ->subject('Intrepid Finance - Application Confirmation')
            ->send();
    }

    public function makeReferenceNumber()
        : string
    {
        if ( ! $this->isLoaded()) {
            throw new Exception('Record must be loaded before reference number can be generated.');
        }

        if ($this->get('reference_number')) {
            return $this->get('reference_number');
        }

        $num = 'C'
             . gmdate('y')
             . str_pad($this->get('id') + 100, 7, '0', STR_PAD_LEFT);

        $this->save([
            'reference_number' => $num,
            'modified'         => gmdate('Y-m-d H:i:s'),
        ]);

        return $this->get('reference_number');
    }

    public function doMxDisconnect()
        : bool
    {
        if ( ! $this->get('mx_user_guid')) {
            return TRUE;
        }

        try {
            $status = (new Mx_User())->setUserGuid($this->get('mx_user_guid'))->deleteUser();
        } catch (Throwable $e) {
            $status = FALSE;
        }

        if ( ! $status) {
            $this->actions[] = 'Failed to disconnect MX User GUID: ' . $this->get('mx_user_guid');
        }

        $this->save([
            'mx_user_guid'    => NULL,
            'mx_member_guid'  => NULL,
            'mx_needs_widget' => 'Y',
            'modified'        => gmdate('Y-m-d H:i:s'),
        ]);

        return $status;
    }

    public function needsMxConnectWidget(bool $deepSearch = FALSE)
        : bool
    {
        return 'Y' === $this->get('mx_needs_widget');
    }

    public function getMxWidgetRequestUrl()
        : ? string
    {
        $mxWidget = (new Mx_WidgetRequest())
            ->setUserGuid($this->getMxUserGuid())
            ->setColorScheme('light')
            ->setCurrentMemberGuid($this->get('mx_member_guid'))
            ->setIncludeTransactions(TRUE)
            ->setIsMobileWebview(FALSE)
            ->setMode('verification')
            ->setWidgetType('connect_widget');

        $mxWidget->requestWidgetUrl();

        return $mxWidget->widgetUrl;
    }

    public function getMxUserGuid()
        : ? string
    {
        if ( ! $this->isLoaded()) {
            throw new Exception('Record must be loaded before MX User GUID can be generated.');
        }

        if ($this->get('mx_user_guid')) {
            return $this->get('mx_user_guid');
        }

        $mxUser = (new Mx_User())
            ->setEmail($this->get('email'))
            ->setId($this->get('reference_number') . '-' . gmdate('YmdHis'))
            ->setIsDisabled(FALSE)
            ->setMetadata([
                'company_name' => $this->get('company_name'),
                'first_name'   => $this->get('first_name'),
                'last_name'    => $this->get('last_name'),
            ]);

        if ($mxUser->createUser()) {
            $this->save([
                'mx_id'        => $mxUser->id,
                'mx_user_guid' => $mxUser->userGuid,
                'modified'     => gmdate('Y-m-d H:i:s'),
            ]);
        }

        return $this->get('mx_user_guid');
    }

    protected function onLoad(Tell_Crypto $crypto, array $row)
        : array
    {
        if ($crypto->isEncrypted($row['dob'])) {
            $row['dob'] = $crypto->decrypt($crypto->removeFlag($row['dob']));
        }

        if ($crypto->isEncrypted($row['ssn'])) {
            $row['ssn'] = $crypto->decrypt($crypto->removeFlag($row['ssn']));
        }

        if ($crypto->isEncrypted($row['tax_id'])) {
            $row['tax_id'] = $crypto->decrypt($crypto->removeFlag($row['tax_id']));
        }

        $row['needs_account'] = ! $row['password'] || 'Abandoned' === $row['status'];

        if ( ! $this->tracker->isLoaded()) {
            $this->tracker->before($row);
        }

        return $row;
    }

    protected function onSave(Tell_Crypto $crypto, array $row, array $raw, $saveResult)
    {
        if ($this->disableTracker || ! $this->tracker->isLoaded()) {
            return $saveResult;
        }

        if ($this->auth) {
            $authorName  = $this->auth->get('first_name') . ' ' . $this->auth->get('last_name');
            $authorTable = $this->auth::RECORD_TABLE;
            $authorId    = $this->auth->get('id');
        } else {
            $authorName  = 'System';
            $authorTable = NULL;
            $authorId    = NULL;
        }

        $callback = function (&$before, &$after, $field) {
            switch ($field) {
                case 'request_type':

                    $before = Tell_Json::decode($before);
                    $after  = Tell_Json::decode($after);
                    $labels = Lexicon::users_request_type(NULL);

                    if ( ! is_array($before)) {
                        $before = [];
                    }

                    if ( ! is_array($after)) {
                        $after = [];
                    }

                    $beforeList = [];
                    $afterList  = [];

                    foreach ($before as $k => $v) {
                        if ('Y' === $v) {
                            $beforeList[] = $labels[$k] ?? ucwords(str_replace('_', ' ', $k));
                        }
                    }

                    foreach ($after as $k => $v) {
                        if ('Y' === $v) {
                            $afterList[] = $labels[$k] ?? ucwords(str_replace('_', ' ', $k));
                        }
                    }

                    $before = implode(', ', $beforeList);
                    $after  = implode(', ', $afterList);

                    break;

                case 'request_amount':
                case 'revenue_annually':
                case 'revenue_monthly':
                case 'money_raised':
                case 'ref_a_payment':
                case 'ref_b_payment':

                    if (is_numeric($before)) {
                        $before = currencyFormat($before);
                    }

                    if (is_numeric($after)) {
                        $after = currencyFormat($after);
                    }

                    break;

                default:
                break;
            };

            return FALSE;
        };

        if ($this->isLoaded()) {
            $this->tracker
                ->author($authorName, $authorTable, $authorId)
                ->parent(static::TABLE, $this->id)
                ->actions($this->actions)
                ->after($row)
                ->archive(self::$trackableFields, $callback, $crypto);

            $this->tracker->reset()->before($row);
        } else {
            $this->tracker->reset();
        }

        $this->actions = NULL;

        return $saveResult;
    }
}
