<?php

/**
 * @link  https://docs.mx.com/api#connect_request_a_url
 */
class Mx_WidgetRequest extends Mx_Api
{
    protected $userGuid                  = NULL;
    protected $widgetUrl                 = NULL;
    protected $clientRedirectUrl         = NULL;
    protected $colorScheme               = NULL;
    protected $currentInstitutionCode    = NULL;
    protected $currentInstitutionGuid    = NULL;
    protected $currentMemberGuid         = NULL;
    protected $disableBackgroundAgg      = NULL;
    protected $disableInstitutionSearch  = NULL;
    protected $includeTransactions       = NULL;
    protected $isMobileWebview           = NULL;
    protected $mode                      = NULL;
    protected $oauthReferralSource       = NULL;
    protected $uiMessageVersion          = NULL;
    protected $uiMessageWebviewUrlScheme = NULL;
    protected $updateCredentials         = NULL;
    protected $widgetType                = NULL;

    public function setUserGuid(?string $userGuid)
        : self
    {
        $this->userGuid = $userGuid;

        return $this;
    }

    public function setClientRedirectUrl($clientRedirectUrl)
        : self
    {
        $this->clientRedirectUrl = $clientRedirectUrl;

        return $this;
    }

    public function setColorScheme($colorScheme)
        : self
    {
        $this->colorScheme = $colorScheme;

        return $this;
    }

    public function setCurrentInstitutionCode($currentInstitutionCode)
        : self
    {
        $this->currentInstitutionCode = $currentInstitutionCode;

        return $this;
    }

    public function setCurrentInstitutionGuid($currentInstitutionGuid)
        : self
    {
        $this->currentInstitutionGuid = $currentInstitutionGuid;

        return $this;
    }

    public function setCurrentMemberGuid($currentMemberGuid)
        : self
    {
        $this->currentMemberGuid = $currentMemberGuid;

        return $this;
    }

    public function setDisableBackgroundAgg($disableBackgroundAgg)
        : self
    {
        $this->disableBackgroundAgg = $disableBackgroundAgg;

        return $this;
    }

    public function setDisableInstitutionSearch($disableInstitutionSearch)
        : self
    {
        $this->disableInstitutionSearch = $disableInstitutionSearch;

        return $this;
    }

    public function setIncludeTransactions($includeTransactions)
        : self
    {
        $this->includeTransactions = $includeTransactions;

        return $this;
    }

    public function setIsMobileWebview($isMobileWebview)
        : self
    {
        $this->isMobileWebview = $isMobileWebview;

        return $this;
    }

    public function setMode($mode)
        : self
    {
        $this->mode = $mode;

        return $this;
    }

    public function setOauthReferralSource($oauthReferralSource)
        : self
    {
        $this->oauthReferralSource = $oauthReferralSource;

        return $this;
    }

    public function setUiMessageVersion($uiMessageVersion)
        : self
    {
        $this->uiMessageVersion = $uiMessageVersion;

        return $this;
    }

    public function setUiMessageWebviewUrlScheme($uiMessageWebviewUrlScheme)
        : self
    {
        $this->uiMessageWebviewUrlScheme = $uiMessageWebviewUrlScheme;

        return $this;
    }

    public function setUpdateCredentials($updateCredentials)
        : self
    {
        $this->updateCredentials = $updateCredentials;

        return $this;
    }

    public function setWidgetType($widgetType)
        : self
    {
        $this->widgetType = $widgetType;

        return $this;
    }

    public function requestWidgetUrl()
        : bool
    {
        if ( ! $this->userGuid) {
            throw new Mx_Exception('Cannot request widget URL. Missing user GUID.');
        }

        $data = [
            'client_redirect_url'           => $this->clientRedirectUrl,
            'color_scheme'                  => $this->colorScheme,
            'current_institution_code'      => $this->currentInstitutionCode,
            'current_institution_guid'      => $this->currentInstitutionGuid,
            'current_member_guid'           => $this->currentMemberGuid,
            'disable_background_agg'        => $this->disableBackgroundAgg,
            'disable_institution_search'    => $this->disableInstitutionSearch,
            'include_transactions'          => $this->includeTransactions,
            'is_mobile_webview'             => $this->isMobileWebview,
            'mode'                          => $this->mode,
            'oauth_referral_source'         => $this->oauthReferralSource,
            'ui_message_version'            => $this->uiMessageVersion,
            'ui_message_webview_url_scheme' => $this->uiMessageWebviewUrlScheme,
            'update_credentials'            => $this->updateCredentials,
            'widget_type'                   => $this->widgetType,
        ];

        $data = array_filter($data, function ($v) {
            return NULL !== $v;
        });

        $data['widget_url'] = $data;

        $url = $this->getEndpoint() . '/users/' . $this->userGuid . '/widget_urls';

        $request = $this->getRequest()
            ->header('Accept-Language', 'en-US')
            ->json($data);

        $response = (new Tell_Client())->post($url, $request);

        if (200 === $response->code()) {
            $this->widgetUrl = $response->body('widget_url.url');
        } else {
            throw (new Mx_Exception())
                ->setMessage('Failed request widget URL.')
                ->setCode($response->code())
                ->setResponse($response);
        }

        return TRUE;
    }
}
