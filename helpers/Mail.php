<?php
/**
 * Mail Helper Class
 * Handles email sending functionality
 */
class Mail {
    /**
     * Send password reset email
     * @param string $to Email address
     * @param string $token Reset token
     * @return bool
     */
    public static function sendPasswordReset($to, $token) {
        $resetLink = BASE_URL . 'auth/reset-password.php?token=' . $token;
        $subject = 'Reset Your Password - ' . APP_NAME;
        
        $message = self::getPasswordResetTemplate($resetLink);
        
        return self::sendMail($to, $subject, $message);
    }
    
    /**
     * Send contact form email
     * @param string $name Sender name
     * @param string $email Sender email
     * @param string $subject Email subject
     * @param string $message Email message
     * @return bool
     */
    public static function sendContactForm($name, $email, $subject, $message) {
        // Send to admin/company email
        $to = defined('MAIL_REPLY_TO') ? MAIL_REPLY_TO : MAIL_FROM_EMAIL;
        $emailSubject = 'Contact Form: ' . $subject;
        
        $emailMessage = self::getContactFormTemplate($name, $email, $subject, $message);
        
        return self::sendMail($to, $emailSubject, $emailMessage);
    }
    
    /**
     * Get contact form email template
     * @param string $name Sender name
     * @param string $email Sender email
     * @param string $subject Email subject
     * @param string $message Email message
     * @return string
     */
    private static function getContactFormTemplate($name, $email, $subject, $message) {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">' . APP_NAME . '</h1>
        <p style="color: white; margin: 10px 0 0 0; font-size: 16px;">New Contact Form Submission</p>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Contact Form Message</h2>
        
        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <p style="margin: 0 0 10px 0;"><strong style="color: #374151;">Name:</strong> ' . htmlspecialchars($name) . '</p>
            <p style="margin: 0 0 10px 0;"><strong style="color: #374151;">Email:</strong> <a href="mailto:' . htmlspecialchars($email) . '" style="color: #f59e0b; text-decoration: none;">' . htmlspecialchars($email) . '</a></p>
            <p style="margin: 0;"><strong style="color: #374151;">Subject:</strong> ' . htmlspecialchars($subject) . '</p>
        </div>
        
        <div style="margin: 20px 0;">
            <h3 style="color: #1f2937; margin-bottom: 10px;">Message:</h3>
            <div style="background: #ffffff; padding: 15px; border-left: 4px solid #f59e0b; border-radius: 4px; white-space: pre-wrap; color: #4b5563;">' . nl2br(htmlspecialchars($message)) . '</div>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 14px; margin: 0;">
                You can reply directly to this email to respond to ' . htmlspecialchars($name) . ' at <a href="mailto:' . htmlspecialchars($email) . '" style="color: #f59e0b;">' . htmlspecialchars($email) . '</a>
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>This is an automated email from the ' . APP_NAME . ' contact form.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Get password reset email template
     * @param string $resetLink
     * @return string
     */
    private static function getPasswordResetTemplate($resetLink) {
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">' . APP_NAME . '</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Reset Your Password</h2>
        
        <p>Hello,</p>
        
        <p>We received a request to reset your password for your ' . APP_NAME . ' account.</p>
        
        <p>Click the button below to reset your password:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="' . htmlspecialchars($resetLink) . '" 
               style="display: inline-block; background: #f59e0b; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px;">
                Reset Password
            </a>
        </div>
        
        <p style="color: #6b7280; font-size: 14px;">
            Or copy and paste this link into your browser:<br>
            <a href="' . htmlspecialchars($resetLink) . '" style="color: #f59e0b; word-break: break-all;">' . htmlspecialchars($resetLink) . '</a>
        </p>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>⚠️ Important:</strong> This link will expire in 1 hour. If you didn\'t request a password reset, please ignore this email.
            </p>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Best regards,<br>
            <strong>' . APP_NAME . ' Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Send email using SMTP (Gmail)
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email body (HTML)
     * @return bool
     */
    private static function sendMail($to, $subject, $message) {
        // Check if SMTP is configured
        if (!defined('MAIL_SMTP_HOST') || empty(MAIL_SMTP_HOST)) {
            // Fallback to PHP mail() if SMTP not configured
            return self::sendMailFallback($to, $subject, $message);
        }
        
        // Use SMTP
        return self::sendSMTP($to, $subject, $message);
    }
    
    /**
     * Send email via SMTP
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email body (HTML)
     * @return bool
     */
    private static function sendSMTP($to, $subject, $message) {
        $smtpHost = MAIL_SMTP_HOST;
        $smtpPort = defined('MAIL_SMTP_PORT') ? MAIL_SMTP_PORT : 587;
        $smtpUser = MAIL_SMTP_USER;
        $smtpPass = MAIL_SMTP_PASS;
        $fromEmail = defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : $smtpUser;
        $fromName = APP_NAME;
        
        $socket = null;
        $errorMsg = '';
        
        try {
            // Use stream_context for TLS support
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            
            // Create socket connection with context
            if ($smtpPort == 465) {
                // SSL connection
                $socket = @stream_socket_client("ssl://$smtpHost:$smtpPort", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
            } else {
                // Plain connection (will upgrade to TLS)
                $socket = @stream_socket_client("tcp://$smtpHost:$smtpPort", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
            }
            
            if (!$socket) {
                $errorMsg = "SMTP Connection failed: $errstr ($errno)";
                error_log($errorMsg);
                return false;
            }
            
            // Set timeout
            stream_set_timeout($socket, 30);
            
            // Read server greeting
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '220') {
                $errorMsg = "SMTP Greeting failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            // Send EHLO
            fputs($socket, "EHLO " . gethostname() . "\r\n");
            $response = self::readResponse($socket);
            
            // Start TLS if port is 587
            if ($smtpPort == 587) {
                fputs($socket, "STARTTLS\r\n");
                $response = self::readResponse($socket);
                if (substr($response, 0, 3) != '220') {
                    $errorMsg = "STARTTLS failed: $response";
                    error_log($errorMsg);
                    fclose($socket);
                    return false;
                }
                
                // Enable crypto
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    $errorMsg = "TLS encryption failed";
                    error_log($errorMsg);
                    fclose($socket);
                    return false;
                }
                
                // Send EHLO again after TLS
                fputs($socket, "EHLO " . gethostname() . "\r\n");
                $response = self::readResponse($socket);
            }
            
            // Authenticate
            fputs($socket, "AUTH LOGIN\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '334') {
                $errorMsg = "AUTH LOGIN failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            fputs($socket, base64_encode($smtpUser) . "\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '334') {
                $errorMsg = "Username authentication failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            fputs($socket, base64_encode($smtpPass) . "\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '235') {
                $errorMsg = "SMTP Authentication failed: $response";
                error_log($errorMsg);
                error_log("Check if App Password is correct and 2-Step Verification is enabled");
                fclose($socket);
                return false;
            }
            
            // Set sender
            fputs($socket, "MAIL FROM: <$fromEmail>\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '250') {
                $errorMsg = "MAIL FROM failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            // Set recipient
            fputs($socket, "RCPT TO: <$to>\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '250') {
                $errorMsg = "RCPT TO failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            // Send data
            fputs($socket, "DATA\r\n");
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '354') {
                $errorMsg = "DATA command failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            // Email headers and body
            $emailData = "From: $fromName <$fromEmail>\r\n";
            $emailData .= "To: <$to>\r\n";
            $emailData .= "Subject: $subject\r\n";
            $emailData .= "MIME-Version: 1.0\r\n";
            $emailData .= "Content-Type: text/html; charset=UTF-8\r\n";
            $emailData .= "Content-Transfer-Encoding: 8bit\r\n";
            $emailData .= "\r\n";
            $emailData .= $message . "\r\n";
            $emailData .= ".\r\n";
            
            fputs($socket, $emailData);
            $response = self::readResponse($socket);
            if (substr($response, 0, 3) != '250') {
                $errorMsg = "Email sending failed: $response";
                error_log($errorMsg);
                fclose($socket);
                return false;
            }
            
            // Quit
            fputs($socket, "QUIT\r\n");
            self::readResponse($socket);
            fclose($socket);
            
            return true;
        } catch (Exception $e) {
            $errorMsg = "SMTP Exception: " . $e->getMessage();
            error_log($errorMsg);
            if ($socket) {
                @fclose($socket);
            }
            return false;
        }
    }
    
    /**
     * Read SMTP response (handles multi-line responses)
     * @param resource $socket
     * @return string
     */
    private static function readResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        return trim($response);
    }
    
    /**
     * Fallback to PHP mail() function
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email body (HTML)
     * @return bool
     */
    private static function sendMailFallback($to, $subject, $message) {
        // Email headers
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . APP_NAME . ' <' . (defined('MAIL_FROM_EMAIL') ? MAIL_FROM_EMAIL : 'noreply@' . parse_url(BASE_URL, PHP_URL_HOST)) . '>',
            'Reply-To: ' . (defined('MAIL_REPLY_TO') ? MAIL_REPLY_TO : 'noreply@' . parse_url(BASE_URL, PHP_URL_HOST)),
            'X-Mailer: PHP/' . phpversion()
        ];
        
        $headersString = implode("\r\n", $headers);
        
        // Send email
        $result = @mail($to, $subject, $message, $headersString);
        
        // Log email sending (for debugging)
        if (!$result) {
            error_log("Failed to send email to: $to");
        }
        
        return $result;
    }
}

