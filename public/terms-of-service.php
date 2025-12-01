<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$pageTitle = 'Terms of Service';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="min-h-[calc(100vh-4rem)] bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-file-contract mr-3 text-primary-600"></i>Terms of Service
                </h1>
                <p class="text-gray-600">Last updated: <?= date('F j, Y') ?></p>
            </div>

            <div class="prose prose-lg max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        By accessing and using PinePix (Pineapple Entrepreneur Information Management System), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Description of Service</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        PinePix is a platform that connects pineapple entrepreneurs, farms, and businesses. Our services include but are not limited to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Farm management and listing</li>
                        <li>Business networking and connections</li>
                        <li>Announcements and news sharing</li>
                        <li>E-commerce marketplace for pineapple products</li>
                        <li>AI-powered chatbot assistance</li>
                        <li>Knowledge base and FAQ resources</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Accounts</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">3.1 Registration</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        To access certain features of our platform, you must register for an account. You agree to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Provide accurate, current, and complete information</li>
                        <li>Maintain and update your information to keep it accurate</li>
                        <li>Maintain the security of your password and identification</li>
                        <li>Accept all responsibility for activities that occur under your account</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">3.2 Account Termination</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We reserve the right to suspend or terminate your account at any time, with or without notice, for conduct that we believe violates these Terms of Service or is harmful to other users, us, or third parties.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. User Conduct</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You agree not to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Use the platform for any illegal purpose or in violation of any laws</li>
                        <li>Post false, misleading, or fraudulent information</li>
                        <li>Harass, abuse, or harm other users</li>
                        <li>Transmit any viruses, malware, or other harmful code</li>
                        <li>Attempt to gain unauthorized access to the platform or other accounts</li>
                        <li>Interfere with or disrupt the platform or servers</li>
                        <li>Copy, modify, or create derivative works of the platform</li>
                        <li>Use automated systems to access the platform without permission</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Content and Intellectual Property</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.1 User Content</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You retain ownership of any content you post on the platform. By posting content, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, modify, and distribute your content for the purpose of operating and promoting the platform.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">5.2 Platform Content</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        All content on the platform, including text, graphics, logos, and software, is the property of PinePix or its content suppliers and is protected by copyright and other intellectual property laws.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. E-Commerce</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If you use our e-commerce features:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>You are responsible for the accuracy of product listings</li>
                        <li>You must comply with all applicable laws and regulations</li>
                        <li>We are not responsible for transactions between buyers and sellers</li>
                        <li>All sales are final unless otherwise stated</li>
                        <li>We reserve the right to remove any listings that violate our policies</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Disclaimers and Limitations of Liability</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        THE PLATFORM IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND. WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        TO THE MAXIMUM EXTENT PERMITTED BY LAW, WE SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, OR ANY LOSS OF PROFITS OR REVENUES.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Indemnification</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You agree to indemnify, defend, and hold harmless PinePix, its officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses (including legal fees) arising out of your use of the platform or violation of these Terms.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Modifications to Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We reserve the right to modify these Terms of Service at any time. We will notify users of any material changes by posting the new Terms on this page and updating the "Last updated" date. Your continued use of the platform after such modifications constitutes acceptance of the updated Terms.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Governing Law</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        These Terms of Service shall be governed by and construed in accordance with the laws of Malaysia, without regard to its conflict of law provisions.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Contact Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If you have any questions about these Terms of Service, please contact us at:
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 mb-2"><strong>Email:</strong> <a href="mailto:pinepixmalaysia@gmail.com" class="text-primary-600 hover:underline">pinepixmalaysia@gmail.com</a></p>
                        <p class="text-gray-700"><strong>Address:</strong> Jalan Hang Tuah Jaya, 76100 Durian Tunggal Melaka, Malaysia</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>

