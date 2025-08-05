@extends('front.layouts.main')

@section('title', 'Terms and Conditions')

@section('content')
    <div class="bg-white dark:bg-gray-800 py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            <article class="prose dark:prose-invert lg:prose-xl max-w-4xl mx-auto space-y-2.5">

                <h1 class="text-4xl font-bold mb-4">Terms and Conditions</h1>

                <p class="lead">Welcome to our SMM Panel. By accessing or using our service, you agree to be bound by these
                    terms and conditions. Please read them carefully.</p>

                <h2>1. Accounts</h2>
                <p>When you create an account with us, you must provide us with information that is accurate, complete, and
                    current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate
                    termination of your account on our Service.</p>

                <h2>2. Orders and Payments</h2>
                <p>All payments are processed through secure payment gateways. We do not store your credit card details. You
                    agree that once you complete a payment, you will not file a dispute or a chargeback against us for any
                    reason.</p>
                <ul>
                    <li>Funds added to your account are non-refundable.</li>
                    <li>Orders placed on our panel are final and cannot be canceled or refunded once they have started
                        processing.</li>
                    <li>If an order cannot be delivered, the funds will be credited back to your account balance.</li>
                </ul>

                <h2>3. Service Usage</h2>
                <p>You agree not to use our services for any illegal activities or to promote content that is hateful,
                    defamatory, or discriminatory. We reserve the right to terminate your account if you violate this
                    policy.</p>

                <h2>4. Limitation of Liability</h2>
                <p>Our service is provided "as is" without any warranties. We are not responsible for any damage you or your
                    business may suffer. We do not guarantee that your new followers or likes will interact with you, we
                    simply guarantee to get you the followers you pay for.</p>

                <h2>5. Changes to Terms</h2>
                <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. We will try
                    to provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material
                    change will be determined at our sole discretion.</p>

                <p>Last updated: {{ date('F j, Y') }}</p>

            </article>

        </div>
    </div>
@endsection
