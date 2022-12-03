<?php

$inspect('Mx Connection', function () {

    $userGuid = NULL;

    $this->try('Mx_User->createUser()', function () use (&$userGuid) {

        $ipsum = new Tell_Ipsum_Contact('US');

        $user = (new Mx_User())
            ->setEmail($ipsum->email())
            ->setId('C' . date('YmdHis') . mt_rand(10, 99))
            ->setIsDisabled(FALSE)
            ->setMetadata([
                'first_name' => 'Bob',
                'last_name'  => 'Smith',
            ]);

        $this->assert($user->createUser())->true();

        $this->assert($user->userGuid)->string();

        $userGuid = $user->userGuid;

    })->then('Mx_WidgetRequest->requestWidgetUrl()', function () use ($userGuid) {

        $widget = (new Mx_WidgetRequest())
            ->setUserGuid($userGuid)
            ->setColorScheme('light')
            ->setIncludeTransactions(TRUE)
            ->setIsMobileWebview(FALSE)
            ->setMode('verification')
            ->setWidgetType('connect_widget');

        $this->assert($widget->requestWidgetUrl())->true();

        $this->validate($widget->widgetUrl)->url()->assert();

    })->then('Mx_User->deleteUser()', function () use ($userGuid) {

        $user = (new Mx_User())->setUserGuid($userGuid);

        $this->assert($user->deleteUser())->true();

    });

});

$inspect('Mx Data', function () {

    $this->try('Mx_Account->listUserAccounts()', function () {

        $account = (new Mx_Account())->setUserGuid($this->user_guid);

        $this->assert($account->listUserAccounts())->true();

        $list = $account->accounts;

        $first = $list[0] ?? NULL;

        $this->assert($list)->array();

        $this->assert($first)->array();

        $this->assert($first['user_guid'] ?? NULL)->equals($this->user_guid);

        $this->validate($first['balance'] ?? NULL)->numeric()->assert();

        $this->assert($account->paginate)->instanceOf(Paginate::class);

    });

    $this->try('Mx_Transaction->listTransactions()', function () {

        $transaction = (new Mx_Transaction())->setUserGuid($this->user_guid);

        $this->assert($transaction->listTransactions())->true();

        $list = $transaction->transactions;

        $first = $list[0] ?? NULL;

        $this->assert($list)->array();

        $this->assert($first)->array();

        $this->assert($first['user_guid'] ?? NULL)->equals($this->user_guid);

        $this->validate($first['amount'] ?? NULL)->numeric()->assert();

        $this->assert($transaction->paginate)->instanceOf(Paginate::class);

    });

})->vars([
    'user_guid'   => 'USR-d785669d-b4bf-4763-abaf-bb653e74c521',
    'member_guid' => 'MBR-323e11f4-bdd2-4159-b714-567cfa8f95a5',
]);
