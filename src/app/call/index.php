
<?php

$app->call('application/commercial-capital')->get(function () {
    return $this->format('public.commercial-capital');
});

$app->call('application/loan-calculator')->get(function () {
    return $this->format('public.loan-calculator');
});

$app->call('application/venture-capital')->get(function () {
    return $this->format('public.venture-capital');
});

$app->call('application/submit')->post(function () {
    return $this->action(Action_Public_Application::class)->onSuccess(function ($action) {
        return $action->token;
    });
});

$app->call('application/abandoned')->post(function () {
    return $this->action(Action_Public_Abandoned::class);
});

$app->call('application/confirmation/{slug}')->get(function (string $token) {
    return $this->format('public.confirmation')->using([
        'token' => $token,
    ]);
});

$app->call('application/create-account/{slug}')->get(function (string $token) {
    return $this->format('public.create-account')->using([
        'token' => $token,
    ]);
});

$app->call('application/create-account/submit')->post(function () {
    return $this->action(Action_Public_CreateAccount::class);
});

$app->call('/')->any(function () {
    return '
        <a href="admin">Admin Dashboard</a><br />
        <a href="application/commercial-capital">Commercial Capital</a><br />
        <a href="application/venture-capital">Growth &amp; Venture Capital</a><br />
        <a href="application/loan-calculator">Loan Calculator</a><br />
    ';
});
