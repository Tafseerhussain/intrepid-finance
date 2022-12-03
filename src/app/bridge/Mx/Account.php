<?php

/**
 * @link  https://docs.mx.com/api#core_resources_accounts_list_accounts
 */
class Mx_Account extends Mx_Api
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
    protected $recordsPerPage = 10;

    /**
     * ) array [
     * )     0 => [
     * )         guid                      => ACT-e927c0f0-fa45-4213-bfe4-9fcde96f454f
     * )         id                        => account-248d59eb-cc02-4fa5-854f-7d00f23824c4
     * )         member_guid               => MBR-323e11f4-bdd2-4159-b714-567cfa8f95a5
     * )         member_id                 => NULL
     * )         user_guid                 => USR-d785669d-b4bf-4763-abaf-bb653e74c521
     * )         user_id                   => C220000101-20221123141105
     * )         account_number            => 4684406678
     * )         apr                       => NULL
     * )         apy                       => NULL
     * )         available_balance         => 1002
     * )         available_credit          => NULL
     * )         balance                   => 1002
     * )         cash_balance              => NULL
     * )         cash_surrender_value      => NULL
     * )         credit_limit              => NULL
     * )         currency_code             => USD
     * )         day_payment_is_due        => 20
     * )         death_benefit             => NULL
     * )         holdings_value            => NULL
     * )         interest_rate             => NULL
     * )         institution_code          => mx_bank_oauth
     * )         insured_name              => NULL
     * )         is_closed                 => FALSE
     * )         is_hidden                 => FALSE
     * )         last_payment              => NULL
     * )         loan_amount               => NULL
     * )         matures_on                => NULL
     * )         member_is_managed_by_user => TRUE
     * )         metadata                  => NULL
     * )         minimum_balance           => NULL
     * )         minimum_payment           => NULL
     * )         name                      => MX Bank Checking
     * )         nickname                  => NULL
     * )         original_balance          => NULL
     * )         pay_out_amount            => NULL
     * )         payoff_balance            => NULL
     * )         premium_amount            => NULL
     * )         routing_number            => 316713272
     * )         started_on                => NULL
     * )         total_account_value       => NULL
     * )         subtype                   => NULL
     * )         type                      => CHECKING
     * )         created_at                => 2022-11-23T14:11:23Z
     * )         imported_at               => 2022-11-23T14:11:23Z
     * )         last_payment_at           => NULL
     * )         payment_due_at            => 2021-05-20T16:24:00Z
     * )         updated_at                => 2022-11-23T14:11:23Z
     * )     ],
     * )     ...
     * ) ]
     * ---
     * @var  array
     */
    protected $accounts = [];

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

    public function listUserAccounts()
        : bool
    {
        if ( ! $this->userGuid) {
            throw new Mx_Exception('Cannot list user accounts. Missing user GUID.');
        }

        $url = $this->getEndpoint() . '/users/' . $this->userGuid . '/accounts';

        $request = $this->getRequest()->data([
            'page'             => $this->page,
            'records_per_page' => $this->recordsPerPage,
        ]);

        $response = (new Tell_Client())->get($url, $request);

        if (200 === $response->code()) {
            $this->accounts = $response->body('accounts');

            $this->paginate->setTotalRows((int) $response->body('pagination.total_entries'));
        } else {
            throw (new Mx_Exception())
                ->setMessage('Failed to list accounts.')
                ->setCode($response->code())
                ->setResponse($response);
        }

        return TRUE;
    }
}
