<?php

// Dependencies
require_once __DIR__ . '/../../public/index.php';

// (object) Instance of Tell_Db
$db = $container->pull(Tell_Db::class);

// Minimum age for abandoned user records to be eligible for processing
$age = gmdate('Y-m-d H:i:s', strtotime('-1 hour'));

// (string) Query all unprocessed abandoned intake form applications more than an hour old
$sql = "
    SELECT id, email
    FROM users
    WHERE form_verified = 'N'
    AND status = 'Abandoned'
    AND deleted IS NULL
    AND created <= ?
    ORDER BY modified DESC
";

// (array) Execute query
$rows = $db->query($sql)->bind($age)->run();

// Loop through each unprocessed abandoned user
foreach ($rows as $row) {
    // (object) Instantiate bridge
    $user = $container->factory(User::class);

    // Goto next unverified abandoned user (we must have deleted this ID earlier in loop)
    if ( ! $user->load($row['id'])) {
        continue;
    }

    // (string) Query to see if any real users exist with this email
    $sql = "
        GRAB id
        FROM users
        WHERE email = ?
        AND status != 'Abandoned'
        AND deleted IS NULL
    ";

    // A real user with this email already exists
    if ($db->query($sql)->bind($row['email'])->run()) {
        // Delete this abandoned user since a real user already exists with this email
        $db->query('DELETE FROM users WHERE id = ? LIMIT 1')->bind($row['id'])->run();

        // Goto next unverified abandoned user
        continue;
    }

    // (string) Delete older abandoned users with this email (remember we ORDER BY modified DESC)
    $sql = "
        DELETE FROM users
        WHERE id != ?
        AND status = 'Abandoned'
        AND deleted IS NULL
    ";

    // Execute query
    $db->query($sql)->bind($row['id'])->run();

    // The minimum fields needed to process this abandoned user have not been filled out
    if (   ! $user->get('email')
        || ! $user->get('first_name')
        || ! $user->get('last_name')
        || ! $user->get('company_name')
    ) {
        // Delete this incomplete abandoned user
        $db->query('DELETE FROM users WHERE id = ?')->bind($row['id'])->run();

        // Goto next unverified abandoned user
        continue;
    }

    // Process this abandoned user
    $user->save([
        'form_verified' => 'Y',
        'modified'      => gmdate('Y-m-d H:i:s'),
    ]);
}
