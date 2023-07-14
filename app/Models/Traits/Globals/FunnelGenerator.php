<?php

namespace App\Models\Traits\Globals;

trait FunnelGenerator
{
    private function welcomeFunnel($referralCode, $funnelType): string
    {
        return config('app.frontend_url') . "/welcome?referral=$referralCode&funnel_type=$funnelType";
    }

    private function webinarFunnel($referralCode, $funnelType): string
    {
        return config('app.frontend_url') . "/webinar?referral=$referralCode&funnel_type=$funnelType";
    }

    private function checkoutFunnel($referralCode, $funnelType): string
    {
        return config('app.frontend_url') . "/register?referral=$referralCode&funnel_type=$funnelType";
    }

    public function masterClassFunnel()
    {
        $funnelType = MASTER_FUNNEL;
        $affiliateCode = $this->affiliate_code;

        return [
            'welcome' => $this->welcomeFunnel($affiliateCode, $funnelType),
            'webinar' => $this->webinarFunnel($affiliateCode, $funnelType),
            'checkout' => $this->checkoutFunnel($affiliateCode, $funnelType),
        ];
    }

    public function liveOpportunityCallFunnel()
    {
        $funnelType = LIVE_OPPORTUNITY_CALL_FUNNEL;
        $affiliateCode = $this->affiliate_code;

        return [
            'welcome' => $this->welcomeFunnel($affiliateCode, $funnelType),
            'webinar' => $this->webinarFunnel($affiliateCode, $funnelType),
            'checkout' => $this->checkoutFunnel($affiliateCode, $funnelType),
        ];
    }
}
