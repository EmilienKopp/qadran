<script lang="ts">
  import { Link } from '@inertiajs/svelte';

  const lastUpdated = 'November 23, 2025';
</script>

<svelte:head>
  <title>Privacy Policy - Qadran</title>
</svelte:head>

<div class="min-h-screen bg-white dark:bg-gray-900">
  <div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8">
      <Link 
        href="/" 
        class="inline-flex items-center text-sm  dark: dark:transition-colors"
      >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Home
      </Link>
    </div>

    <article class="prose prose-lg dark:prose-invert max-w-none">
      <h1 class="text-4xl font-bold  dark:text-white mb-4">Privacy Policy</h1>
      <p class="text-sm  dark: mb-8">Last Updated: {lastUpdated}</p>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">1. Introduction</h2>
        <p class=" dark: mb-4">
          Welcome to Qadran ("we," "our," or "us"). We are committed to protecting your privacy and personal information. 
          This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our 
          time tracking and project management platform.
        </p>
        <p class=" dark:">
          By using Qadran, you agree to the collection and use of information in accordance with this policy. 
          If you do not agree with our policies and practices, please do not use our services.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">2. Information We Collect</h2>
        
        <h3 class="text-xl font-semibold  dark:text-white mb-3">2.1 Personal Information</h3>
        <p class=" dark: mb-4">We may collect the following personal information:</p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Name and contact information (email address)</li>
          <li>Account credentials (username and encrypted password)</li>
          <li>Profile information you choose to provide</li>
          <li>Organization and team membership information</li>
          <li>Payment and billing information</li>
        </ul>

        <h3 class="text-xl font-semibold  dark:text-white mb-3">2.2 Usage Information</h3>
        <p class=" dark: mb-4">We automatically collect information about your use of our services:</p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Time tracking data and activity logs</li>
          <li>Project and task information</li>
          <li>Voice assistant usage and audio recordings (if enabled)</li>
          <li>Device information and browser type</li>
          <li>IP address and location data</li>
          <li>Usage patterns and feature interactions</li>
        </ul>

        <h3 class="text-xl font-semibold  dark:text-white mb-3">2.3 Third-Party Integrations</h3>
        <p class=" dark:">
          When you connect third-party services (such as GitHub, Jira, or other integrations), 
          we collect and store authentication tokens and data necessary to provide integration functionality. 
          We only access the minimum data required for the integration to function.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">3. How We Use Your Information</h2>
        <p class=" dark: mb-4">We use the information we collect to:</p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Provide, maintain, and improve our services</li>
          <li>Process your transactions and manage your account</li>
          <li>Enable time tracking, reporting, and project management features</li>
          <li>Facilitate team collaboration and communication</li>
          <li>Send you technical notices, updates, and support messages</li>
          <li>Respond to your comments, questions, and customer service requests</li>
          <li>Monitor and analyze trends, usage, and activities</li>
          <li>Detect, prevent, and address technical issues and fraudulent activity</li>
          <li>Comply with legal obligations and enforce our terms of service</li>
        </ul>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">4. Data Sharing and Disclosure</h2>
        
        <h3 class="text-xl font-semibold  dark:text-white mb-3">4.1 Within Your Organization</h3>
        <p class=" dark: mb-4">
          Your activity data, time entries, and project information may be visible to other members of your 
          organization or team, as determined by your organization's settings and access permissions.
        </p>

        <h3 class="text-xl font-semibold  dark:text-white mb-3">4.2 Service Providers</h3>
        <p class=" dark: mb-4">
          We may share your information with third-party service providers who perform services on our behalf, such as:
        </p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Cloud hosting and storage providers</li>
          <li>Payment processors</li>
          <li>Analytics services</li>
          <li>Email and communication platforms</li>
        </ul>

        <h3 class="text-xl font-semibold  dark:text-white mb-3">4.3 Legal Requirements</h3>
        <p class=" dark: mb-4">
          We may disclose your information if required to do so by law or in response to valid requests by 
          public authorities (e.g., a court or government agency), or to protect our rights, privacy, safety, 
          or property, or that of our users or the public.
        </p>

        <h3 class="text-xl font-semibold  dark:text-white mb-3">4.4 Business Transfers</h3>
        <p class=" dark:">
          In the event of a merger, acquisition, reorganization, or sale of assets, your information may be 
          transferred to the acquiring entity. We will notify you of any such change in ownership or control.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">5. Data Security</h2>
        <p class=" dark: mb-4">
          We implement appropriate technical and organizational measures to protect your personal information:
        </p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Encryption of data in transit and at rest</li>
          <li>Regular security assessments and updates</li>
          <li>Access controls and authentication mechanisms</li>
          <li>Secure data centers with physical security measures</li>
          <li>Employee training on data protection practices</li>
        </ul>
        <p class=" dark:">
          However, no method of transmission over the Internet or electronic storage is 100% secure. 
          While we strive to use commercially acceptable means to protect your information, we cannot 
          guarantee its absolute security.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">6. Data Retention</h2>
        <p class=" dark:">
          We retain your personal information for as long as necessary to fulfill the purposes outlined in 
          this Privacy Policy, unless a longer retention period is required or permitted by law. When you 
          delete your account, we will delete or anonymize your personal information within a reasonable 
          timeframe, except where we are required to retain it for legal, regulatory, or security purposes.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">7. Your Rights and Choices</h2>
        <p class=" dark: mb-4">You have the following rights regarding your personal information:</p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li><strong>Access:</strong> Request a copy of the personal information we hold about you</li>
          <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
          <li><strong>Deletion:</strong> Request deletion of your personal information</li>
          <li><strong>Portability:</strong> Request transfer of your data to another service</li>
          <li><strong>Objection:</strong> Object to processing of your information in certain circumstances</li>
          <li><strong>Restriction:</strong> Request restriction of processing your information</li>
          <li><strong>Withdrawal:</strong> Withdraw consent where processing is based on consent</li>
        </ul>
        <p class=" dark:">
          To exercise these rights, please contact us using the information provided in the Contact section below.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">8. Cookies and Tracking Technologies</h2>
        <p class=" dark: mb-4">
          We use cookies and similar tracking technologies to track activity on our service and hold certain information:
        </p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li><strong>Essential Cookies:</strong> Required for the service to function properly</li>
          <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
          <li><strong>Analytics Cookies:</strong> Help us understand how you use our service</li>
          <li><strong>Authentication Cookies:</strong> Keep you logged in securely</li>
        </ul>
        <p class=" dark:">
          You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. 
          However, if you do not accept cookies, you may not be able to use some portions of our service.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">9. Children's Privacy</h2>
        <p class=" dark:">
          Our service is not intended for children under the age of 13 (or the applicable age of digital consent 
          in your jurisdiction). We do not knowingly collect personal information from children. If you are a parent 
          or guardian and believe your child has provided us with personal information, please contact us, and we 
          will take steps to delete such information.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">10. International Data Transfers</h2>
        <p class=" dark:">
          Your information may be transferred to and maintained on computers located outside of your state, province, 
          country, or other governmental jurisdiction where data protection laws may differ. We will take all 
          reasonable steps to ensure that your data is treated securely and in accordance with this Privacy Policy.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">11. Changes to This Privacy Policy</h2>
        <p class=" dark:">
          We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new 
          Privacy Policy on this page and updating the "Last Updated" date at the top. You are advised to review 
          this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they 
          are posted on this page.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">12. Contact Us</h2>
        <p class=" dark: mb-4">
          If you have any questions about this Privacy Policy, please contact us:
        </p>
        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
          <p class=" dark: mb-2">
            <strong>Email:</strong> <a href="mailto:privacy@qadran.com" class="text-blue-600 dark:text-blue-400 hover:underline">privacy@qadran.com</a>
          </p>
          <p class=" dark:">
            <strong>Website:</strong> <a href="/" class="text-blue-600 dark:text-blue-400 hover:underline">qadran.com</a>
          </p>
        </div>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">13. GDPR Compliance (European Users)</h2>
        <p class=" dark: mb-4">
          If you are located in the European Economic Area (EEA), you have additional rights under the General 
          Data Protection Regulation (GDPR):
        </p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>The right to lodge a complaint with your supervisory authority</li>
          <li>The right to be informed about our data processing activities</li>
          <li>The right to receive information about data breaches affecting you</li>
        </ul>
        <p class=" dark:">
          Our lawful bases for processing your personal data include: consent, contractual necessity, 
          legal obligations, and legitimate interests. You may withdraw consent at any time where we rely 
          on consent as the legal basis for processing.
        </p>
      </section>

      <section class="mb-8">
        <h2 class="text-2xl font-semibold  dark:text-white mb-4">14. California Privacy Rights (CCPA)</h2>
        <p class=" dark: mb-4">
          If you are a California resident, you have specific rights under the California Consumer Privacy Act (CCPA):
        </p>
        <ul class="list-disc pl-6 mb-4  dark: space-y-2">
          <li>Right to know what personal information is collected, used, shared, or sold</li>
          <li>Right to delete personal information held by us</li>
          <li>Right to opt-out of the sale of personal information (we do not sell your information)</li>
          <li>Right to non-discrimination for exercising your CCPA rights</li>
        </ul>
        <p class=" dark:">
          To exercise these rights, please contact us using the information in the Contact section.
        </p>
      </section>
    </article>

    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
      <p class="text-center text-sm  dark:">
        © {new Date().getFullYear()} Qadran. All rights reserved.
      </p>
    </div>
  </div>
</div>
