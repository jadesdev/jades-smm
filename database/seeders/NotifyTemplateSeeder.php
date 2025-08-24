<?php

namespace Database\Seeders;

use App\Models\NotifyTemplate;
use Illuminate\Database\Seeder;

class NotifyTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'type' => 'WELCOME',
                'name' => 'User Welcome Email',
                'title' => 'Welcome to the Platform',
                'message' => 'Welcome, {{name}}! We\'re thrilled to have you. Explore our services and start growing your presence today.',
                'subject' => 'Welcome to {{site_name}} – Your Account is Ready!',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>Welcome to <strong>{{site_name}}</strong>! Your email has been verified and your account is now fully active. We are thrilled to have you join our community.</p>
                    <p>You can now log in and start exploring our wide range of services designed to help you succeed. Here are a few things you can do to get started:</p>
                    <ul>
                        <li><strong>Place an Order:</strong> Browse our services and place your first order.</li>
                        <li><strong>Add Funds:</strong> Easily add funds to your wallet for quick checkouts.</li>
                        <li><strong>Check our API:</strong> For developers, integrate our services directly into your applications.</li>
                        <li><strong>Invite Friends:</strong> Earn rewards through our referral program.</li>
                    </ul>
                    <p>If you have any questions or need assistance, our support team is here to help. Just reply to this email or open a ticket on our support page.</p>
                    <p>Thank you for choosing {{site_name}}!</p>
                ',
                'shortcodes' => ([
                    'name' => 'User full name.',
                    'first_name' => 'User first name.',
                    'email' => 'User email.',
                    'login_link' => 'Login page link.',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email']),
            ],
            [
                'type' => 'EMAIL_VERIFICATION',
                'name' => 'User Email Verification',
                'title' => 'Verify Your Email',
                'message' => 'Please verify your email address to complete your registration for {{site_name}}.',
                'subject' => 'Please Verify Your Email Address for {{site_name}}',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>Thank you for registering at <strong>{{site_name}}</strong>. There\'s just one more step to activate your account and start using our services.</p>
                    <p>Please click the button below to verify that this is your email address. This link will expire in 60 minutes for your security.</p>
                    <p>If you did not create an account with us, please disregard this email.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User full name.',
                    'verification_link' => 'Verification link',
                    'verification_code' => 'Verification code',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email']),
            ],
            [
                'type' => 'PASSWORD_RESET',
                'name' => 'User Password Reset',
                'title' => 'Password Reset Request',
                'message' => 'A request to reset the password for your {{site_name}} account has been received.',
                'subject' => 'Reset Your Password for {{site_name}}',
                'content' => '
                    <p>Hello {{name}},</p>
                    <p>We received a request to reset the password associated with your account at <strong>{{site_name}}</strong>.</p>
                    <p>If you made this request, please click the button below to set a new password. For your security, this link is only valid for the next 60 minutes.</p>
                    <p>If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged and your account is secure. Please do not forward or share this link with anyone.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User full name.',
                    'reset_link' => 'Reset link',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email']),
            ],
            [
                'type' => 'ORDER_STATUS_CHANGE',
                'name' => 'Order Status Update',
                'title' => 'Your Order #{{order_id}} is now {{order_status}}',
                'message' => 'The status of your order for "{{service_name}}" has been updated.',
                'subject' => 'Update on your Order #{{order_id}}: Now {{order_status}}',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>This is a quick update on your order <strong>#{{order_id}}</strong> for the service "{{service_name}}".</p>
                    <p>The new status is now: <strong>{{order_status}}</strong></p>
                    <p>Remaining quantity: <strong>{{remains}}</strong></p>
                    <p>If your order status is "Partial" or "Refunded", any applicable funds have been automatically returned to your wallet balance.</p>
                    <p>You can view the full details of this order by visiting your order history page.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User full name.',
                    'order_id' => 'Order ID.',
                    'service_name' => 'Service name.',
                    'remains' => 'Order remains.',
                    'order_status' => 'Order status.',
                    'refund_amount' => 'Refund amount.',
                ]),
                'email_status' => true,
                'push_status' => true,
                'inapp_status' => true,
                'channels' => (['email', 'push', 'inapp']),
            ],
            [
                'type' => 'DEPOSIT_SUCCESSFUL',
                'name' => 'Funds Added to Wallet',
                'title' => 'Your deposit of {{deposit_amount}} was successful!',
                'message' => 'The funds have been added to your wallet. Your new balance is {{new_balance}}.',
                'subject' => 'We\'ve Received Your Deposit of {{deposit_amount}}',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>This is a confirmation that your deposit was successful and the funds have been added to your account wallet.</p>
                    
                    <table class="details-table" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin: 20px 0; border-collapse: collapse;">
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Amount Deposited:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{deposit_amount}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Payment Method:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{payment_gateway}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Transaction ID:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_id}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Deposit Details:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{deposit_details}}</td></tr>
                        <tr><td style="padding: 8px; font-weight: bold; width: 40%;">New Wallet Balance:</td><td style="padding: 8px;">{{new_balance}}</td></tr>
                    </table>
                    
                    <p>Thank you for your payment. You can now use your balance to purchase any of our services.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User name.',
                    'deposit_amount' => 'Amount deposited.',
                    'payment_gateway' => 'Gateway used.',
                    'deposit_details' => 'Deposit details.',
                    'transaction_code' => 'Transaction Code.',
                    'new_balance' => 'New wallet balance.',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email', 'push', 'inapp']),
            ],
            [
                'type' => 'REFERRAL_EARNING',
                'name' => 'New Referral Commission',
                'title' => 'You\'ve earned a commission!',
                'message' => 'You just earned {{commission_amount}} from a referral. Your new bonus balance is {{referrer_bonus_balance}}.',
                'subject' => 'Congratulations! You\'ve Earned a {{commission_amount}} Referral Commission',
                'content' => '
                <p>Hi {{referrer_name}},</p>
                <p>Great news! You have earned a commission of <strong>{{commission_amount}}</strong> because your referred user, <strong>{{user_name}}</strong>, made a qualifying deposit or purchase.</p>
                <p>The commission has been added to your bonus balance, which you can use for services on our platform.</p>
                <p>Keep up the great work! Share your referral link with more friends to keep the rewards coming.</p>
                ',
                'shortcodes' => ([
                    'referrer_name' => 'Referrers name.',
                    'user_name' => 'User name.',
                    'commission_amount' => 'Commission amount.',
                    'referrer_bonus_balance' => 'Referrer\'s new balance.',
                    'referral_dashboard_link' => 'Link to referral page.',
                ]),
                'email_status' => true,
                'push_status' => true,
                'inapp_status' => true,
                'channels' => (['email', 'push', 'inapp']),
            ],
            [
                'type' => 'SUPPORT_TICKET_OPENED_USER',
                'name' => 'Support Ticket Opened (User)',
                'title' => 'Your Ticket #{{ticket_id}} has been created.',
                'message' => 'We have received your support request and will get back to you shortly.',
                'subject' => '[Ticket #{{ticket_id}}] Your Support Request has been Received',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>Thank you for reaching out to our support team. This email is to confirm that we have successfully received your request and have opened a support ticket for you.</p>
                    <p>A member of our team will review your request and get back to you as soon as possible.</p>
                    
                    <table class="details-table" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin: 20px 0; border-collapse: collapse;">
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Ticket ID:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">#{{ticket_id}}</td></tr>
                        <tr><td style="padding: 8px; font-weight: bold; width: 40%;">Subject:</td><td style="padding: 8px;">{{ticket_subject}}</td></tr>
                    </table>
                    
                    <p>You can view your ticket and add more comments by clicking the button below.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User name.',
                    'ticket_id' => 'Ticket ID.',
                    'ticket_subject' => 'Ticket subject.',
                    'ticket_link' => 'Link to view the ticket.',
                ]),
                'email_status' => true,
                'push_status' => true,
                'inapp_status' => true,
                'channels' => (['email', 'push', 'inapp']),
            ],
            [
                'type' => 'SUPPORT_TICKET_REPLY_USER',
                'name' => 'Admin Reply to Ticket (User)',
                'title' => 'New reply on your ticket #{{ticket_id}}',
                'message' => 'A support agent has replied to your ticket regarding "{{ticket_subject}}".',
                'subject' => 'Re: [Ticket #{{ticket_id}}] New Reply from our Support Team',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>A member of our support team has replied to your ticket <strong>#{{ticket_id}}</strong> regarding "{{ticket_subject}}".</p>
                    <p>Please log in to your account to view the full reply and respond if your issue is not yet resolved.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User name.',
                    'ticket_id' => 'Ticket ID.',
                    'ticket_subject' => 'Ticket subject.',
                    'ticket_link' => 'Link to view the reply.',
                    'reply_preview' => 'A short preview of the reply.',
                ]),
                'email_status' => true,
                'push_status' => true,
                'inapp_status' => true,
                'channels' => (['email', 'push', 'inapp']),
            ],
            [
                'type' => 'SUPPORT_TICKET_OPENED_ADMIN',
                'name' => 'New Support Ticket (Admin)',
                'title' => 'New Ticket #{{ticket_id}} from {{user_name}}',
                'message' => 'A new support ticket has been submitted and requires attention.',
                'subject' => '[New Ticket #{{ticket_id}}] A New Support Request Requires Attention',
                'content' => '
                    <p>Hello Admin,</p>
                    <p>A new support ticket has been submitted by a user and requires your team\'s attention.</p>
            
                    <table class="details-table" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin: 20px 0; border-collapse: collapse;">
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">User:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{user_name}} ({{user_email}})</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Ticket ID:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">#{{ticket_id}}</td></tr>
                        <tr><td style="padding: 8px; font-weight: bold; width: 40%;">Subject:</td><td style="padding: 8px;">{{ticket_subject}}</td></tr>
                    </table>
                    
                    <p>Please log in to the admin panel to review the ticket and respond in a timely manner.</p>
                ',
                'shortcodes' => ([
                    'user_name' => 'User name.',
                    'user_email' => 'User email.',
                    'ticket_id' => 'Ticket ID.',
                    'ticket_subject' => 'Ticket subject.',
                    'admin_ticket_link' => 'Link to ticket in admin panel.',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email']),
            ],
            [
                'type' => 'TRANSACTION_NOTIFICATION',
                'name' => 'General Transaction Notification',
                'title' => 'A new transaction has occurred on your account',
                'message' => 'A {{transaction_type}} of {{transaction_amount}} occurred. Details: {{transaction_details}}.',
                'subject' => 'Transaction Notification: A {{transaction_type}} of {{transaction_amount}} on Your Account',
                'content' => '  
                    <p>Hi {{name}},</p>
                    <p>This is to notify you of a recent transaction on your <strong>{{site_name}}</strong> wallet. Please find the details below.</p>
                    
                    <table class="details-table" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin: 20px 0; border-collapse: collapse;">
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Transaction ID:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_code}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Date:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_date}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Transaction Type:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_type}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Amount:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_amount}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Details:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{transaction_details}}</td></tr>
                        <tr><td style="padding: 8px; font-weight: bold; width: 40%;">New Wallet Balance:</td><td style="padding: 8px;">{{new_balance}}</td></tr>
                    </table>
                    
                    <p>If you have any questions about this transaction or did not authorize it, please contact our support team immediately.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User name.',
                    'first_name' => 'User first name.',
                    'transaction_code' => 'Transaction Code.',
                    'transaction_date' => 'Date of transaction.',
                    'transaction_type' => 'Type (e.g., Debit, Credit).',
                    'transaction_service' => 'Service name.',
                    'transaction_amount' => 'Transaction amount.',
                    'transaction_details' => 'Description of the transaction.',
                    'new_balance' => 'User\'s new wallet balance.',
                ]),
                'email_status' => true,
                'push_status' => false,
                'inapp_status' => false,
                'channels' => (['email']),
            ],
            [
                'type' => 'ORDER_COMPLETED',
                'name' => 'Order Successfully Completed',
                'title' => 'Order #{{order_id}} completed successfully! 🎉',
                'message' => 'Your order for "{{service_name}}" has been completed successfully. Thank you for your business!',
                'subject' => 'Order #{{order_id}} Completed Successfully!',
                'content' => '
                    <p>Hi {{name}},</p>
                    <p>Fantastic news! Your order <strong>#{{order_id}}</strong> for "{{service_name}}" has been completed successfully.</p>
                    
                    <table class="details-table" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width:100%; margin: 20px 0; border-collapse: collapse;">
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Order ID:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">#{{order_id}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Service:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{service_name}}</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Delivered:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"> {{delivered}} /</td></tr>
                        <tr><td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold; width: 40%;">Completion Date:</td><td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{completion_date}}</td></tr>
                        <tr><td style="padding: 8px; font-weight: bold; width: 40%;">Total Charged:</td><td style="padding: 8px;">{{order_amount}}</td></tr>
                    </table>
                    
                    <p>Thank you for choosing our services! We hope you are satisfied with the results.</p>
                    <p>If you have any questions about your completed order or need support, please don\'t hesitate to contact us.</p>
                    <p><strong>Ready for more?</strong> Browse our services to place your next order.</p>
                ',
                'shortcodes' => ([
                    'name' => 'User full name.',
                    'order_id' => 'Order ID.',
                    'service_name' => 'Service name.',
                    'order_quantity' => 'Original quantity ordered.',
                    'remains' => 'quantity remaining.',
                    'delivered' => 'quantity delivered.',
                    'completion_date' => 'Date order was completed.',
                    'order_amount' => 'Final amount charged.',
                ]),
                'email_status' => true,
                'push_status' => true,
                'inapp_status' => true,
                'channels' => (['email', 'push', 'inapp']),
            ],

        ];

        foreach ($templates as $templateData) {
            NotifyTemplate::updateOrCreate(
                ['type' => $templateData['type']],
                $templateData
            );
        }
    }
}
