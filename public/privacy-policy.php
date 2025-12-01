<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$pageTitle = 'Privacy Policy';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="min-h-[calc(100vh-4rem)] bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12">
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-shield-alt mr-3 text-primary-600"></i>Privacy Policy
                </h1>
                <p class="text-gray-600">Last updated: <?= date('F j, Y') ?></p>
            </div>

            <div class="prose prose-lg max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Welcome to PinePix (Pineapple Entrepreneur Information Management System). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Personal Information</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may collect personal information that you provide to us, including:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Name, email address, and contact information</li>
                        <li>Farm and business information</li>
                        <li>Profile information and photographs</li>
                        <li>Payment information (processed securely through third-party providers)</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">2.2 Automatically Collected Information</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may automatically collect certain information when you use our platform, including:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>IP address and browser type</li>
                        <li>Device information and operating system</li>
                        <li>Usage data and interaction patterns</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process transactions and manage your account</li>
                        <li>Send you updates, newsletters, and promotional materials (with your consent)</li>
                        <li>Respond to your inquiries and provide customer support</li>
                        <li>Detect, prevent, and address technical issues</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We do not sell your personal information. We may share your information only in the following circumstances:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>With your explicit consent</li>
                        <li>To comply with legal obligations or respond to legal requests</li>
                        <li>To protect our rights, privacy, safety, or property</li>
                        <li>With service providers who assist us in operating our platform (under strict confidentiality agreements)</li>
                        <li>In connection with a business transfer or merger</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet or electronic storage is 100% secure.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Your Rights</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Access and receive a copy of your personal information</li>
                        <li>Rectify inaccurate or incomplete information</li>
                        <li>Request deletion of your personal information</li>
                        <li>Object to processing of your personal information</li>
                        <li>Request restriction of processing</li>
                        <li>Data portability</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Cookies</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We use cookies and similar tracking technologies to track activity on our platform and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Children's Privacy</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our platform is not intended for children under the age of 18. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Changes to This Privacy Policy</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If you have any questions about this Privacy Policy, please contact us at:
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

