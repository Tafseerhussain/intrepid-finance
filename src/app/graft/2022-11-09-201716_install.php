<?php

/**
 * Base installation.
 * ---
 * @version  2022-11-09T20:17:16+00:00
 */
return new class($db) extends Tell_Graft {
    /**
     * Apply database graft.
     */
    public function do()
    {
        $this->create('admins', function (Tell_Schema_Column $column) {
            $column->int('id')->primary()->increment();
            $column->firstName('first_name');
            $column->lastName('last_name');
            $column->email('email');
            $column->password('password');
            $column->enum('status', ['Active', 'Disabled'])->null(FALSE)->default('Active');
            $column->enum('access_level', ['1', '2', '3'])->null(FALSE)->default('1');
            $column->ipAddress('last_ip');
            $column->dateTime('modified');
            $column->dateTime('created');
            $column->dateTime('deleted');
        }, [
            'first_name'   => 'Steve',
            'last_name'    => 'Iskander',
            'email'        => 'steve@intrepidfinance.io',
            'password'     => Tell_Password::hash('testing'),
            'status'       => 'Active',
            'access_level' => 3,
            'last_ip'      => '0.0.0.0',
            'modified'     => date('Y-m-d H:i:s'),
            'created'      => date('Y-m-d H:i:s'),
        ]);

        $this->create('users', function (Tell_Schema_Column $column) {
            $formTypes = [
                'commercial_capital',
                'venture_capital',
            ];

            $formVerified = [
                'Y',
                'N',
            ];

            $corpTypes = [
                'llc',
                's_corp',
                'c_corp',
            ];

            $creditScores = [
                '720+',
                '720-625',
                '625-550',
                '550-',
                'unknown',
            ];

            $statuses = [
                'Abandoned',
                'Prospect',
                'Started',
                'Completed',
                'Working',
                'Outsourced',
                'Funded',
                'Declined',
                'Archive',
                'Inactive',
            ];

            $column->int('id')->primary()->increment();
            $column->enum('form_type', $formTypes);
            $column->varchar('form_token', 40);
            $column->enum('form_verified', $formVerified)->default('N')->null(FALSE);
            $column->varchar('reference_number', 255);
            $column->money('request_amount');
            $column->text('request_type'); // JSON
            $column->company('company_name');
            $column->firstName('first_name');
            $column->lastName('last_name');
            $column->email('email');
            $column->phone('phone1');
            $column->phone('phone2');
            $column->text('dob', 255); // Encrypted
            $column->text('ssn', 255); // Encrypted
            $column->smallInt('years_in_business');
            $column->text('tax_id', 255); // Encrypted
            $column->money('revenue_annually');
            $column->money('revenue_monthly');
            $column->varchar('churn_rate', 128);
            $column->varchar('previous_financier', 128);
            $column->money('money_raised');
            $column->enum('corp_type', $corpTypes);
            $column->enum('credit_score', $creditScores);
            $column->address1('business_address1');
            $column->address2('business_address2');
            $column->city('business_city');
            $column->province('business_province');
            $column->postal('business_postal');
            $column->country('business_country');
            $column->address1('home_address1');
            $column->address2('home_address2');
            $column->city('home_city');
            $column->province('home_province');
            $column->postal('home_postal');
            $column->country('home_country');
            $column->entity('ref_a_name');
            $column->phone('ref_a_phone');
            $column->money('ref_a_payment');
            $column->entity('ref_b_name');
            $column->phone('ref_b_phone');
            $column->money('ref_b_payment');
            $column->password('password')->null(TRUE)->default(NULL);
            $column->varchar('password_token');
            $column->dateTime('password_date');
            $column->enum('status', $statuses)->default('Abandoned')->null(FALSE);
            $column->ipAddress('last_ip');
            $column->varchar('mx_id', 255);
            $column->varchar('mx_user_guid', 255);
            $column->varchar('mx_member_guid', 255);
            $column->enum('mx_needs_widget', ['Y', 'N'])->null(FALSE)->default('Y');
            $column->dateTime('modified');
            $column->dateTime('created');
            $column->dateTime('deleted');
        });

        $this->create('users_notes', function (Tell_Schema_Column $column) {
            $column->int('id')->primary()->increment();
            $column->int('user_id');
            $column->int('admin_id');
            $column->enum('author_type', ['Admin', 'System'])->default('System');
            $column->fullName('author_name')->default('System');
            $column->text('note');
            $column->ipAddress('ip_address');
            $column->dateTime('modified');
            $column->dateTime('created');
            $column->dateTime('deleted');
        });
    }

    /**
     * Revert database graft.
     */
    public function undo()
    {
        $this->drop('admins');
        $this->drop('users');
        $this->drop('users_notes');
    }
};
