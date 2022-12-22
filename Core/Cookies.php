<?php
namespace App\Core;

class Cookies 
{


    public function AreCookieSet(): bool
    {
        if(!isset($_COOKIE['cookieConsent']))
        {
            return true;
        }return false;
    }


    public function verifyPostCookieRequest()
    {
        if(isset($_POST['cookieConsent']) && $_POST['cookieConsent'] === 'true')
        {
            unset($_POST['CookieConsent']);
            $this->createCookie('cookieConsent');
            foreach($_POST as $key => $values)
            {
                $this->createCookie($key);
            }
        }else if (isset($_POST['rejectCookie']))
        {
            unset($_POST['rejectCookie']);
            $this->setCookieDenied('cookieConsent');
            foreach($_POST as $key => $values)
            {
                $this->setCookieDenied($key);
            }
        }
    }


    private function createCookie($k): void
    {
        $expire =  time() + 60 * 60 * 30;
        setcookie($k , 'true' , $expire);
    }

    private function setCookieDenied($k): void
    {
        $expire = 0;
        setcookie($k , 'false' , $expire);
    }



// GOOGLE ANALYTICS COOKIES 

public function handleGoogleCookies()
{
    if(isset($_COOKIE['googleAnalytics']) && $_COOKIE['googleAnalytics'] === 'true')
    {
        $trackingId = 'G-0F3WD6XZTC';
        return $this->renderGoogleAnalyticsJs($trackingId);
    }
}

private function renderGoogleAnalyticsJs(string $trackingId)
{
    echo <<<EOF
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=$trackingId"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '$trackingId');
    </script>
    EOF;
}








}