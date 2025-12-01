<?php
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->setSession($user);
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    public function register($data) {
        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO users (role, name, email, password_hash, phone, address, gender, ic_passport, business_category) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['role'] ?? 'entrepreneur',
            $data['name'],
            $data['email'],
            $passwordHash,
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['gender'] ?? null,
            $data['ic_passport'] ?? null,
            $data['business_category'] ?? null
        ]);
        
        if ($result) {
            $userId = $this->db->lastInsertId();
            $user = $this->getUserById($userId);
            $this->setSession($user);
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    public function forgotPassword($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email not found'];
        }
        
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$token, $expiry, $email]);
        
        // Send email with reset link
        require_once __DIR__ . '/Mail.php';
        $emailSent = Mail::sendPasswordReset($email, $token);
        
        if ($emailSent) {
            return ['success' => true, 'message' => 'Password reset link has been sent to your email address. Please check your inbox.'];
        } else {
            // Even if email fails, don't expose the token for security
            // Log the error for admin to see
            error_log("Failed to send password reset email to: $email");
            return ['success' => true, 'message' => 'If an account exists with this email, a password reset link has been sent. Please check your inbox.'];
        }
    }
    
    public function resetPassword($token, $newPassword) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Invalid or expired reset token'];
        }
        
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $result = $stmt->execute([$passwordHash, $user['id']]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Password reset successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to reset password'];
    }
    
    public function setSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    }
    
    public function logout() {
        session_destroy();
        session_start();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    public function isEntrepreneur() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'entrepreneur';
    }
    
    public function getUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->getUserById($_SESSION['user_id']);
    }
    
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login.php');
            exit;
        }
    }
    
    public function requireAdmin() {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . 'index.php');
            exit;
        }
    }
}
