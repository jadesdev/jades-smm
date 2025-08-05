<?php

namespace App\Http\Controllers;

use App\Traits\HomeServiceTrait;

class HomeController extends Controller
{
    use HomeServiceTrait;

    public function index()
    {
        $testimonials = collect($this->testimonialList())->shuffle()->take(3)->values();
        $faqs = $this->faqList();

        $platforms = $this->getHomePlatforms();
        $services = $this->getHomeServices();

        return view('front.index', compact('faqs', 'testimonials', 'platforms', 'services'));
    }

    public function terms()
    {
        return view('front.terms');
    }

    public function privacy()
    {
        return view('front.privacy');
    }

    public function apiDocs()
    {
        return view('front.api-docs');
    }

    public function howItWorks()
    {
        return view('front.how-it-works');
    }

    private function testimonialList()
    {
        return [
            [
                'name' => 'Chioma Okafor',
                'title' => 'Digital Marketer',
                'quote' => 'The best SMM panel I\'ve ever used. The delivery is incredibly fast and the support team is always helpful. Highly recommended!',
            ],
            [
                'name' => 'Tunde Balogun',
                'title' => 'Agency Owner',
                'quote' => 'I run a small agency and this panel is a lifesaver. The API is robust and the prices are unbeatable for resellers.',
            ],
            [
                'name' => 'Amina Bello',
                'title' => 'Content Creator',
                'quote' => 'Affordable, reliable, and super easy to use. I was able to grow my personal brand\'s engagement significantly in just a few weeks.',
            ],
            [
                'name' => 'Kwame Mensah',
                'title' => 'Entrepreneur',
                'quote' => 'I love how simple and efficient the platform is. It saves me hours every week on social growth tasks.',
            ],
            [
                'name' => 'Zanele Dlamini',
                'title' => 'Influencer',
                'quote' => 'The results speak for themselves. I gained over 5K real followers in less than a month.',
            ],
            [
                'name' => 'Femi Adeyemi',
                'title' => 'Brand Strategist',
                'quote' => 'Reliable delivery and amazing customer service. I trust this panel with all my campaigns.',
            ],
            [
                'name' => 'Ngozi Nwosu',
                'title' => 'Social Media Manager',
                'quote' => 'It has all the tools I need for multiple clients. The panel interface is intuitive and efficient.',
            ],
            [
                'name' => 'Kofi Boateng',
                'title' => 'YouTuber',
                'quote' => 'Their YouTube services are top-notch. Views and likes are delivered smoothly.',
            ],
            [
                'name' => 'Fatoumata Diallo',
                'title' => 'PR Consultant',
                'quote' => 'I recommend this panel to all my clients. It gives them the boost they need to get noticed.',
            ],
            [
                'name' => 'Ifeanyi Eze',
                'title' => 'Marketing Director',
                'quote' => 'The analytics and drip-feed options are impressive. A very professional service.',
            ],
        ];
    }

    private function faqList()
    {
        $settings = get_setting();

        return [
            [
                'question' => "What is {$settings->name}?",
                'answer' => "{$settings->name} is an SMM (Social Media Marketing) Panel is an online shop where you can buy social media services like followers, likes, views, etc. It\'s a one-stop-shop for enhancing your social media presence quickly and affordably.",
            ],
            [
                'question' => 'Are the services safe to use?',
                'answer' => 'Absolutely. We provide high-quality, stable services that are safe for your accounts. We never ask for your password, and our methods comply with the terms of service of all major social media platforms.',
            ],
            [
                'question' => 'How long does it take for my order to be delivered?',
                'answer' => 'Most of our services start delivering instantly, within seconds or minutes of placing an order. Each service has an estimated start time mentioned in its description.',
            ],
            [
                'question' => 'What is Drip-feed?',
                'answer' => 'Drip-feed is a feature that allows you to get your order delivered gradually over a period of time instead of all at once. For example, you can get 100 likes per day for 10 days, making the growth look more natural.',
            ],
            [
                'question' => 'Do I need to share my password?',
                'answer' => 'No, never. We do not ask for your account password at any stage of the process. Your privacy and security are our priority.',
            ],
            [
                'question' => 'Can I cancel an order after placing it?',
                'answer' => 'Unfortunately, once an order is in progress, it usually cannot be canceled. However, you can contact support to check if cancellation is possible based on the order status.',
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept a wide range of payment options including credit/debit cards, PayPal, cryptocurrencies, and more depending on your location.',
            ],
            [
                'question' => 'Will the followers/likes drop over time?',
                'answer' => 'Some services may experience a small drop due to platform purges or natural attrition. We offer refill guarantees for eligible services—check the service description for details.',
            ],
            [
                'question' => 'Is there a minimum or maximum order limit?',
                'answer' => 'Each service has its own minimum and maximum limits. These will be displayed on the order page when selecting a service.',
            ],
            [
                'question' => 'Can I place bulk orders?',
                'answer' => 'Yes, we support bulk ordering. You can use our mass order feature to place multiple orders at once.',
            ],
        ];
    }
}
