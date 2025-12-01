<?php
session_start();
require_once __DIR__ . '/../config/autoload.php';

$auth = new Auth();
$db = Database::getInstance()->getConnection();
$currentPage = 'chatbot';

// Get chat history for logged in users
$chatHistory = [];
if ($auth->isLoggedIn()) {
    $stmt = $db->prepare("SELECT * FROM chat_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$_SESSION['user_id']]);
    $chatHistory = array_reverse($stmt->fetchAll());
}

$pageTitle = 'AI Chatbot';
include VIEWS_PATH . 'partials/header.php';
?>

<div class="flex min-h-[calc(100vh-4rem)] bg-gradient-to-br from-gray-50 to-gray-100">
    <?php if ($auth->isLoggedIn()): ?>
        <?php include VIEWS_PATH . 'partials/sidebar.php'; ?>
    <?php endif; ?>
    
    <div class="flex-1 flex flex-col w-full lg:w-auto">
        <div class="flex-1 p-4 sm:p-6 lg:p-8 w-full">
            <div class="max-w-4xl mx-auto w-full">
                <!-- Header Section -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 flex items-center">
                            <i class="fas fa-robot mr-2 sm:mr-3 text-primary-600"></i>
                            <span>AI Chatbot</span>
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600">Get instant answers to your questions</p>
                    </div>
                    <?php if (!$auth->isLoggedIn()): ?>
                        <a href="<?= BASE_URL ?>auth/login.php" class="btn-primary inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base w-full sm:w-auto">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login for Full Access
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border-2 border-gray-200 overflow-hidden">
                    <div class="px-4 py-4 sm:px-6 sm:py-5 bg-gradient-to-r from-primary-50 to-amber-50 border-b-2 border-gray-200">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-comments mr-2 sm:mr-3 text-primary-600 text-base sm:text-lg"></i>
                            <span>Chat</span>
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="mb-3 sm:mb-4">
                            <div class="inline-flex rounded-lg border border-gray-300 overflow-hidden w-full sm:w-auto" role="group">
                                <input type="radio" class="hidden peer/faq" name="chatMode" id="modeFAQ" value="faq" checked>
                                <label for="modeFAQ" class="px-3 sm:px-4 py-2 text-xs sm:text-sm border-r border-gray-300 bg-white text-gray-700 cursor-pointer hover:bg-gray-50 peer-checked/faq:bg-primary-600 peer-checked/faq:text-white transition flex-1 sm:flex-none text-center sm:text-left">
                                    <i class="fas fa-question-circle mr-1 sm:mr-2"></i>
                                    <span class="hidden sm:inline">FAQ Mode</span>
                                    <span class="sm:hidden">FAQ</span>
                                </label>
                                
                                <?php if ($auth->isLoggedIn()): ?>
                                    <input type="radio" class="hidden peer/ai" name="chatMode" id="modeAI" value="ai">
                                    <label for="modeAI" class="px-3 sm:px-4 py-2 text-xs sm:text-sm bg-white text-gray-700 cursor-pointer hover:bg-gray-50 peer-checked/ai:bg-primary-600 peer-checked/ai:text-white transition flex-1 sm:flex-none text-center sm:text-left">
                                        <i class="fas fa-brain mr-1 sm:mr-2"></i>
                                        <span class="hidden sm:inline">AI Mode</span>
                                        <span class="sm:hidden">AI</span>
                                    </label>
                                <?php else: ?>
                                    <label class="px-3 sm:px-4 py-2 text-xs sm:text-sm bg-gray-100 text-gray-400 cursor-not-allowed flex-1 sm:flex-none text-center sm:text-left">
                                        <i class="fas fa-lock mr-1 sm:mr-2"></i>
                                        <span class="hidden sm:inline">AI Mode (Login Required)</span>
                                        <span class="sm:hidden">AI (Locked)</span>
                                    </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div id="chatContainer" class="h-[350px] sm:h-[450px] lg:h-[500px] overflow-y-auto border-2 border-gray-300 rounded-lg p-3 sm:p-4 bg-gray-50 mb-3 sm:mb-4">
                            <?php foreach ($chatHistory as $chat): ?>
                                <div class="chat-message user mb-3">
                                    <div class="chat-bubble text-sm sm:text-base">
                                        <?= nl2br(htmlspecialchars($chat['message'])) ?>
                                    </div>
                                    <small class="text-gray-500 text-xs mt-1 block"><?= Helper::formatDate($chat['created_at'], 'h:i A') ?></small>
                                </div>
                                <div class="chat-message bot mb-3">
                                    <div class="chat-bubble text-sm sm:text-base">
                                        <?= nl2br(htmlspecialchars($chat['response'])) ?>
                                    </div>
                                    <small class="text-gray-500 text-xs mt-1 block">
                                        <?= Helper::formatDate($chat['created_at'], 'h:i A') ?>
                                        <span class="ml-2 px-2 py-0.5 bg-gray-200 text-gray-700 rounded text-xs font-medium"><?= strtoupper($chat['mode']) ?></span>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                            <div id="chatMessages"></div>
                            <div id="typingIndicator" class="hidden">
                                <div class="chat-message bot">
                                    <div class="chat-bubble">
                                        <span class="inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin mr-2"></span>Typing...
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 sm:gap-3">
                            <input type="text" id="chatInput" placeholder="Type your message..." 
                                   class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm sm:text-base">
                            <button class="btn-primary px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base whitespace-nowrap" type="button" id="sendBtn">
                                <i class="fas fa-paper-plane mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Send</span>
                                <span class="sm:hidden">Send</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const chatContainer = document.getElementById('chatContainer');
const chatInput = document.getElementById('chatInput');
const sendBtn = document.getElementById('sendBtn');
const chatMessages = document.getElementById('chatMessages');
const typingIndicator = document.getElementById('typingIndicator');
let currentMode = 'faq';

// Update mode
document.querySelectorAll('input[name="chatMode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        currentMode = this.value;
    });
});

function addMessage(message, isUser = true, mode = 'faq') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${isUser ? 'user' : 'bot'} mb-3`;
    
    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble';
    bubble.innerHTML = message.replace(/\n/g, '<br>');
    
    const time = document.createElement('small');
    time.className = 'text-muted';
    time.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    if (!isUser) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary ms-2';
        badge.textContent = mode.toUpperCase();
        time.appendChild(badge);
    }
    
    messageDiv.appendChild(bubble);
    messageDiv.appendChild(time);
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function scrollToBottom() {
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showTyping() {
    typingIndicator.classList.remove('hidden');
    scrollToBottom();
}

function hideTyping() {
    typingIndicator.classList.add('hidden');
}

async function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;
    
    // Add user message
    addMessage(message, true);
    chatInput.value = '';
    
    // Show typing indicator
    showTyping();
    
    try {
        const response = await fetch('<?= BASE_URL ?>api/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                mode: currentMode
            })
        });
        
        const data = await response.json();
        hideTyping();
        
        if (data.success) {
            addMessage(data.response, false, data.mode);
        } else {
            addMessage('Error: ' + (data.error || 'Something went wrong'), false);
        }
    } catch (error) {
        hideTyping();
        addMessage('Error connecting to chat service. Please try again.', false);
    }
}

sendBtn.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Scroll to bottom on load
scrollToBottom();
</script>

<style>
/* Mobile-specific improvements for chatbot page */
@media (max-width: 640px) {
    /* Ensure form inputs don't zoom on iOS */
    input[type="text"],
    textarea {
        font-size: 16px !important;
    }
}

/* Ensure dashboard content is accessible on mobile */
@media (max-width: 1023px) {
    /* Full width on mobile when sidebar is hidden */
    .flex.min-h-\[calc\(100vh-4rem\)\] > .flex-1 {
        width: 100%;
        margin-left: 0;
    }
}
</style>

<?php include VIEWS_PATH . 'partials/footer.php'; ?>
