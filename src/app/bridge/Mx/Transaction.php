<?php

/**
 * @link  https://docs.mx.com/api#core_resources_transactions_list_transactions_by_user
 */
class Mx_Transaction extends Mx_Api
{
    /**
     * @var  string
     */
    protected $userGuid = NULL;

    /**
     * @var  int
     */
    protected $page = 1;

    /**
     * @var  int
     */
    protected $recordsPerPage = 25;

    /**
     * ) array [
     * )     0 => [
     * )         category                  => Restaurants
     * )         created_at                => 2022-11-23T14:11:24Z
     * )         date                      => 2022-11-23
     * )         posted_at                 => 2022-11-24T12:00:00Z
     * )         status                    => POSTED
     * )         top_level_category        => Food & Dining
     * )         transacted_at             => 2022-11-23T12:00:00Z
     * )         type                      => DEBIT
     * )         updated_at                => 2022-11-23T14:11:24Z
     * )         account_guid              => ACT-e927c0f0-fa45-4213-bfe4-9fcde96f454f
     * )         account_id                => account-248d59eb-cc02-4fa5-854f-7d00f23824c4
     * )         amount                    => 53.12
     * )         category_guid             => CAT-006862be-64a0-e778-f035-0936445b9c16
     * )         check_number_string       => NULL
     * )         currency_code             => USD
     * )         description               => Olive Garden
     * )         extended_transaction_type => NULL
     * )         guid                      => TRN-0f75051e-83aa-4865-823c-3ce02b408ce9
     * )         id                        => transfer-ba2c77b6-09d9-4a8f-8249-be64902c276d
     * )         is_bill_pay               => FALSE
     * )         is_direct_deposit         => FALSE
     * )         is_expense                => TRUE
     * )         is_fee                    => FALSE
     * )         is_income                 => FALSE
     * )         is_international          => NULL
     * )         is_overdraft_fee          => FALSE
     * )         is_payroll_advance        => FALSE
     * )         is_recurring              => NULL
     * )         is_subscription           => FALSE
     * )         latitude                  => NULL
     * )         localized_description     => NULL
     * )         localized_memo            => NULL
     * )         longitude                 => NULL
     * )         member_guid               => MBR-323e11f4-bdd2-4159-b714-567cfa8f95a5
     * )         member_is_managed_by_user => TRUE
     * )         memo                      => NULL
     * )         merchant_category_code    => NULL
     * )         merchant_guid             => MCH-d788bf42-44fd-a266-cf73-721b6dfb92c9
     * )         merchant_location_guid    => NULL
     * )         metadata                  => NULL
     * )         original_description      => Olive Garden
     * )         user_guid                 => USR-d785669d-b4bf-4763-abaf-bb653e74c521
     * )         user_id                   => C220000101-20221123141105
     * )     ],
     * )     ...
     * ) ]
     * ---
     * @var  array
     */
    protected $transactions = [];

    /**
     * @var  Paginate
     */
    protected $paginate = NULL;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->paginate = (new Paginate())
            ->setActivePage($this->page)
            ->setRowsPerPage($this->recordsPerPage)
            ->setLinkSpan(3);
    }

    public function setUserGuid(?string $guid)
        : self
    {
        $this->userGuid = $guid;

        return $this;
    }

    public function setPage(int $page)
        : self
    {
        $this->page = $page;

        $this->paginate->setActivePage($page);

        return $this;
    }

    public function setRecordsPerPage(int $recordsPerPage)
        : self
    {
        $this->recordsPerPage = $recordsPerPage;

        $this->paginate->setRowsPerPage($recordsPerPage);

        return $this;
    }

    public function listTransactions()
        : bool
    {
        if ( ! $this->userGuid) {
            throw new Mx_Exception('Cannot list transactions by user. Missing user GUID.');
        }

        $url = $this->getEndpoint() . '/users/' . $this->userGuid . '/transactions';

        $request = $this->getRequest()->data([
            'page'             => $this->page,
            'records_per_page' => $this->recordsPerPage,
        ]);

        $response = (new Tell_Client())->get($url, $request);

        if (200 === $response->code()) {
            $this->transactions = $response->body('transactions');

            $this->paginate->setTotalRows((int) $response->body('pagination.total_entries'));
        } else {
            throw (new Mx_Exception())
                ->setMessage('Failed to list transactions by user.')
                ->setCode($response->code())
                ->setResponse($response);
        }

        return TRUE;
    }
}
