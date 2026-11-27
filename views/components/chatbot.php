<?php
declare(strict_types=1);

/**
 * HOMEIQ Website-Wide AI Customer Support Chatbot Component
 */
$rawUri = $_SERVER['REQUEST_URI'] ?? '';
$currentPath = trim((string)parse_url($rawUri, PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if (!empty($scriptDir) && str_starts_with($currentPath, $scriptDir)) {
    $currentPath = trim(substr($currentPath, strlen($scriptDir)), '/');
}

$pageType = 'home';
$productId = '';
$productSlug = '';

if ($currentPath === '' || $currentPath === 'home') {
    $pageType = 'home';
} elseif (str_starts_with($currentPath, 'product/')) {
    $pageType = 'product';
    $productSlug = substr($currentPath, strlen('product/'));
} elseif (str_starts_with($currentPath, 'warranty')) {
    $pageType = 'warranty';
} elseif (str_starts_with($currentPath, 'contact')) {
    $pageType = 'contact';
} elseif (str_starts_with($currentPath, 'order-tracking')) {
    $pageType = 'tracking';
} elseif (str_starts_with($currentPath, 'shop') || str_starts_with($currentPath, 'products')) {
    $pageType = 'shop';
} elseif (str_starts_with($currentPath, 'cart')) {
    $pageType = 'cart';
}
?>

<!-- Floating AI Chatbot Launcher (Circular Bot Logo Icon, Positioned Above Cart) -->
<div id="hiqChatbotLauncher" 
     class="hiq-ai-launcher" 
     title="Chat with HOMEIQ Assistant" 
     role="button" 
     aria-label="Open HOMEIQ Support Chatbot" 
     tabindex="0"
     data-page="<?= e($pageType) ?>"
     data-product-slug="<?= e($productSlug) ?>">
  <div class="hiq-ai-launcher-pulse"></div>
  <div class="hiq-ai-launcher-icon">
    <i class="bi bi-robot hiq-icon-bot"></i>
    <i class="bi bi-x-lg hiq-icon-close d-none"></i>
  </div>
  <span class="hiq-ai-status-dot" title="Online"></span>
</div>

<!-- AI Chatbot Window -->
<div id="hiqChatbotWindow" 
     class="hiq-ai-window" 
     aria-hidden="true" 
     role="dialog" 
     aria-labelledby="hiqAiBotTitle">
  
  <!-- Window Header -->
  <div class="hiq-ai-header">
    <div class="d-flex align-items-center gap-2">
      <div class="hiq-ai-avatar">
        <i class="bi bi-robot"></i>
        <span class="hiq-ai-header-dot"></span>
      </div>
      <div>
        <h6 id="hiqAiBotTitle" class="hiq-ai-title mb-0">HOMEIQ Assistant</h6>
        <small class="hiq-ai-subtitle">AI Customer Support • Online</small>
      </div>
    </div>
    <div class="hiq-ai-header-actions">
      <button type="button" class="hiq-ai-action-btn" id="hiqAiResetBtn" title="Clear Conversation" aria-label="Clear chat">
        <i class="bi bi-arrow-clockwise"></i>
      </button>
      <button type="button" class="hiq-ai-action-btn" id="hiqAiMinimizeBtn" title="Minimize Chat" aria-label="Minimize chat">
        <i class="bi bi-dash-lg"></i>
      </button>
      <button type="button" class="hiq-ai-action-btn" id="hiqAiCloseBtn" title="Close Chat" aria-label="Close chat">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  </div>

  <!-- Messages Body -->
  <div class="hiq-ai-body" id="hiqAiMessages">
    <!-- Messages injected dynamically by JS -->
  </div>

  <!-- Footer Input Form -->
  <div class="hiq-ai-footer">
    <form id="hiqAiForm" class="hiq-ai-form" autocomplete="off">
      <input type="text" 
             id="hiqAiInput" 
             class="hiq-ai-input" 
             placeholder="Ask anything about HOMEIQ products, warranty..." 
             maxlength="500" 
             aria-label="Ask a question">
      <button type="submit" class="hiq-ai-send-btn" id="hiqAiSendBtn" aria-label="Send message" title="Send Message">
        <i class="bi bi-send-fill"></i>
      </button>
    </form>
    <div class="hiq-ai-branding">
      <i class="bi bi-shield-check text-warning me-1"></i> Powered by HOMEIQ AI Knowledge Engine
    </div>
  </div>
</div>

<style>
/* ==========================================================================
   HOMEIQ AI Chatbot Component Styles
   ========================================================================== */

/* Circular Floating Bot Launcher Icon */
.hiq-ai-launcher {
  position: fixed;
  bottom: 96px;
  right: 26px;
  z-index: 1040;
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
  color: #FE6B25;
  border: 2px solid #FE6B25;
  cursor: pointer;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.35), 0 2px 8px rgba(254, 107, 37, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  user-select: none;
}

.hiq-ai-launcher:hover {
  transform: translateY(-3px) scale(1.06);
  background: #FE6B25;
  color: #FFFFFF;
  border-color: #FE6B25;
  box-shadow: 0 12px 28px rgba(254, 107, 37, 0.45);
}

.hiq-ai-launcher-pulse {
  position: absolute;
  top: -4px;
  left: -4px;
  right: -4px;
  bottom: -4px;
  border-radius: 50%;
  border: 2px solid #FE6B25;
  opacity: 0;
  animation: hiqAiPulse 2.8s infinite;
  pointer-events: none;
}

@keyframes hiqAiPulse {
  0% { transform: scale(0.96); opacity: 0.8; }
  60% { transform: scale(1.35); opacity: 0; }
  100% { transform: scale(1.35); opacity: 0; }
}

.hiq-ai-launcher-icon {
  font-size: 1.55rem;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hiq-ai-status-dot {
  position: absolute;
  top: 1px;
  right: 1px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #10B981;
  border: 2px solid #0F172A;
  box-shadow: 0 0 6px #10B981;
}

.hiq-ai-launcher.hiq-launcher-active {
  background: #FE6B25;
  color: #FFFFFF;
  border-color: #FE6B25;
}

.hiq-ai-launcher.hiq-launcher-active .hiq-icon-bot {
  display: none !important;
}

.hiq-ai-launcher.hiq-launcher-active .hiq-icon-close {
  display: inline-block !important;
}

/* Chat Window */
.hiq-ai-window {
  position: fixed;
  bottom: 96px;
  right: 26px;
  z-index: 1050;
  width: 385px;
  max-width: calc(100vw - 32px);
  height: 560px;
  max-height: calc(100vh - 120px);
  background: #FFFFFF;
  border-radius: 18px;
  box-shadow: 0 16px 48px rgba(15, 23, 42, 0.28), 0 4px 16px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(226, 232, 240, 0.9);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  opacity: 0;
  visibility: hidden;
  transform: translateY(20px) scale(0.96);
  transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

.hiq-ai-window.hiq-window-open {
  opacity: 1;
  visibility: visible;
  transform: translateY(0) scale(1);
}

/* Header */
.hiq-ai-header {
  background: #0F172A;
  color: #FFFFFF;
  padding: 14px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 2px solid #FE6B25;
  flex-shrink: 0;
}

.hiq-ai-avatar {
  position: relative;
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: linear-gradient(135deg, #FE6B25 0%, #FE4A1F 100%);
  color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.hiq-ai-header-dot {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #10B981;
  border: 2px solid #0F172A;
}

.hiq-ai-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #FFFFFF;
  line-height: 1.2;
}

.hiq-ai-subtitle {
  font-size: 0.72rem;
  color: #94A3B8;
  display: block;
}

.hiq-ai-header-actions {
  display: flex;
  align-items: center;
  gap: 6px;
}

.hiq-ai-action-btn {
  background: transparent;
  border: none;
  color: #94A3B8;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.hiq-ai-action-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  color: #FFFFFF;
}

/* Body / Messages */
.hiq-ai-body {
  flex: 1;
  overflow-y: auto;
  padding: 16px 14px;
  background: #F8FAFC;
  display: flex;
  flex-direction: column;
  gap: 12px;
  scroll-behavior: smooth;
}

/* Scrollbar */
.hiq-ai-body::-webkit-scrollbar {
  width: 5px;
}
.hiq-ai-body::-webkit-scrollbar-track {
  background: #F1F5F9;
}
.hiq-ai-body::-webkit-scrollbar-thumb {
  background: #CBD5E1;
  border-radius: 4px;
}

/* Message Rows */
.hiq-msg-row {
  display: flex;
  align-items: flex-end;
  gap: 8px;
  animation: hiqMsgIn 0.22s ease-out;
}

@keyframes hiqMsgIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

.hiq-msg-bot {
  justify-content: flex-start;
}

.hiq-msg-user {
  justify-content: flex-end;
}

.hiq-msg-bubble {
  max-width: 84%;
  padding: 10px 14px;
  border-radius: 14px;
  font-size: 0.86rem;
  line-height: 1.48;
  word-break: break-word;
}

.hiq-msg-bot .hiq-msg-bubble {
  background: #FFFFFF;
  color: #0F172A;
  border-bottom-left-radius: 4px;
  border: 1px solid #E2E8F0;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}

.hiq-msg-bot .hiq-msg-bubble p:last-child {
  margin-bottom: 0;
}

.hiq-msg-bot .hiq-msg-bubble a {
  color: #FE6B25;
  font-weight: 600;
  text-decoration: underline;
}

.hiq-msg-bot .hiq-msg-bubble a:hover {
  color: #E05312;
}

.hiq-msg-user .hiq-msg-bubble {
  background: #0F172A;
  color: #FFFFFF;
  border-bottom-right-radius: 4px;
}

.hiq-msg-time {
  font-size: 0.68rem;
  color: #94A3B8;
  margin-top: 4px;
  display: block;
}

.hiq-msg-user .hiq-msg-time {
  text-align: right;
  color: #CBD5E1;
}

/* Quick Action / Suggested Question Chips */
.hiq-chips-container {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 8px;
  width: 100%;
}

.hiq-chip-btn {
  background: #FFFFFF;
  border: 1.5px solid rgba(254, 107, 37, 0.35);
  color: #0F172A;
  border-radius: 10px;
  padding: 8px 12px;
  font-size: 0.8rem;
  font-weight: 600;
  text-align: left;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  transition: all 0.18s ease;
  line-height: 1.35;
}

.hiq-chip-btn:hover {
  background: #FFF7ED;
  border-color: #FE6B25;
  color: #FE6B25;
  transform: translateX(2px);
}

.hiq-chip-btn i {
  color: #FE6B25;
  font-size: 0.85rem;
  flex-shrink: 0;
}

/* Action Links Button in Bot Reply */
.hiq-action-links {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 8px;
}

.hiq-action-link-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: #FE6B25;
  color: #FFFFFF !important;
  text-decoration: none !important;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 5px 10px;
  border-radius: 6px;
  transition: background 0.18s ease;
}

.hiq-action-link-btn:hover {
  background: #E05312;
}

/* Typing Indicator */
.hiq-typing-indicator {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 10px 14px;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 14px;
  border-bottom-left-radius: 4px;
}

.hiq-typing-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #FE6B25;
  animation: hiqTyping 1.3s infinite;
}

.hiq-typing-dot:nth-child(2) { animation-delay: 0.2s; }
.hiq-typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes hiqTyping {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
  30% { transform: translateY(-4px); opacity: 1; }
}

/* Footer / Input Area */
.hiq-ai-footer {
  padding: 10px 14px;
  background: #FFFFFF;
  border-top: 1px solid #E2E8F0;
  flex-shrink: 0;
}

.hiq-ai-form {
  display: flex;
  align-items: center;
  gap: 8px;
}

.hiq-ai-input {
  flex: 1;
  height: 40px;
  border-radius: 20px;
  border: 1.5px solid #E2E8F0;
  padding: 0 14px;
  font-size: 0.86rem;
  color: #0F172A;
  background: #F8FAFC;
  outline: none;
  transition: all 0.2s ease;
  font-family: inherit;
}

.hiq-ai-input:focus {
  border-color: #FE6B25;
  background: #FFFFFF;
  box-shadow: 0 0 0 3px rgba(254, 107, 37, 0.12);
}

.hiq-ai-send-btn {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: #FE6B25;
  border: none;
  color: #FFFFFF;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  flex-shrink: 0;
  font-size: 0.95rem;
}

.hiq-ai-send-btn:hover {
  background: #E05312;
  transform: scale(1.05);
}

.hiq-ai-send-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.hiq-ai-branding {
  text-align: center;
  font-size: 0.68rem;
  color: #94A3B8;
  margin-top: 6px;
}

/* Mobile Responsiveness & Bottom Nav Accommodation */
@media (max-width: 767.98px) {
  .hiq-ai-launcher {
    bottom: 135px;
    right: 16px;
    width: 50px;
    height: 50px;
  }

  .hiq-ai-window {
    bottom: 135px;
    right: 12px;
    left: 12px;
    width: auto;
    max-width: none;
    height: 520px;
  }
}
</style>

<script>
(function() {
  const launcher = document.getElementById('hiqChatbotLauncher');
  const chatWindow = document.getElementById('hiqChatbotWindow');
  const closeBtn = document.getElementById('hiqAiCloseBtn');
  const minimizeBtn = document.getElementById('hiqAiMinimizeBtn');
  const resetBtn = document.getElementById('hiqAiResetBtn');
  const messagesBox = document.getElementById('hiqAiMessages');
  const form = document.getElementById('hiqAiForm');
  const input = document.getElementById('hiqAiInput');
  const sendBtn = document.getElementById('hiqAiSendBtn');

  if (!launcher || !chatWindow || !messagesBox) return;

  const pageContext = {
    page: launcher.dataset.page || 'home',
    product_slug: launcher.dataset.productSlug || '',
    url: window.location.pathname
  };

  const WELCOME_MESSAGE = "Hi! Welcome to HOMEIQ. 👋\n\nI'm your HOMEIQ virtual assistant. I can help you explore our smart home products, understand product specifications, check warranty information, get purchase assistance, and answer questions about our services.\n\nHow can I help you today?";

  const DEFAULT_SUGGESTIONS = [
    "Explore HOMEIQ Products",
    "Help Me Choose a Smart Lock",
    "Product Specifications",
    "Warranty Registration",
    "Warranty & Service Information",
    "Order & Delivery Assistance",
    "Returns & Refunds",
    "Installation Assistance",
    "Request a Callback",
    "Contact Customer Support"
  ];

  let isOpen = false;
  let isSending = false;

  // Global Trigger Hook: window.openHomeiqChatbot(optionalQuestion)
  window.openHomeiqChatbot = function(initialQuestion) {
    toggleChat(true);
    if (initialQuestion) {
      setTimeout(() => {
        handleUserSend(initialQuestion);
      }, 250);
    }
  };

  function toggleChat(forceOpen) {
    isOpen = (forceOpen !== undefined && forceOpen !== null) ? forceOpen : !isOpen;
    if (isOpen) {
      chatWindow.classList.add('hiq-window-open');
      launcher.classList.add('hiq-launcher-active');
      chatWindow.setAttribute('aria-hidden', 'false');
      sessionStorage.setItem('hiq_ai_chat_open', '1');
      if (input) {
        setTimeout(() => input.focus(), 250);
      }
      scrollToBottom();
    } else {
      chatWindow.classList.remove('hiq-window-open');
      launcher.classList.remove('hiq-launcher-active');
      chatWindow.setAttribute('aria-hidden', 'true');
      sessionStorage.setItem('hiq_ai_chat_open', '0');
    }
  }

  launcher.addEventListener('click', () => toggleChat());
  launcher.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      toggleChat();
    }
  });

  if (closeBtn) closeBtn.addEventListener('click', () => toggleChat(false));
  if (minimizeBtn) minimizeBtn.addEventListener('click', () => toggleChat(false));
  if (resetBtn) resetBtn.addEventListener('click', () => resetConversation());

  function scrollToBottom() {
    if (messagesBox) {
      messagesBox.scrollTop = messagesBox.scrollHeight;
    }
  }

  function formatTime() {
    const d = new Date();
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Simple Markdown Renderer
  function renderMarkdown(text) {
    if (!text) return '';
    let html = escapeHtml(text);

    // Headers
    html = html.replace(/^### (.*$)/gim, '<strong class="d-block fs-6 mb-1 text-dark">$1</strong>');
    html = html.replace(/^## (.*$)/gim, '<strong class="d-block fs-6 mb-1 text-dark">$1</strong>');

    // Bold & Italics
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/_(.*?)_/g, '<em>$1</em>');

    // Markdown Links [Label](url)
    html = html.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2">$1</a>');

    // Unordered lists
    html = html.replace(/^\- (.*$)/gim, '<div class="d-flex gap-1 mb-1"><span class="text-warning">•</span><span>$1</span></div>');

    // Checkmarks
    html = html.replace(/^✓ (.*$)/gim, '<div class="d-flex gap-1 mb-1"><span class="text-success fw-bold">✓</span><span>$1</span></div>');

    // Line breaks
    html = html.replace(/\n\n/g, '<div class="my-2"></div>');
    html = html.replace(/\n/g, '<br>');

    return html;
  }

  function appendUserBubble(text) {
    const row = document.createElement('div');
    row.className = 'hiq-msg-row hiq-msg-user';
    row.innerHTML = `
      <div class="hiq-msg-bubble">
        ${escapeHtml(text)}
        <span class="hiq-msg-time">${formatTime()}</span>
      </div>
    `;
    messagesBox.appendChild(row);
    scrollToBottom();
  }

  function appendBotBubble(markdownText, suggestions, actions) {
    const row = document.createElement('div');
    row.className = 'hiq-msg-row hiq-msg-bot';

    let html = `
      <div class="hiq-msg-bubble">
        ${renderMarkdown(markdownText)}
    `;

    // Action Links
    if (actions && actions.length > 0) {
      html += `<div class="hiq-action-links">`;
      actions.forEach(act => {
        html += `<a href="${act.url}" class="hiq-action-link-btn"><i class="bi bi-box-arrow-up-right me-1"></i>${escapeHtml(act.label)}</a>`;
      });
      html += `</div>`;
    }

    // Suggested Question Chips
    if (suggestions && suggestions.length > 0) {
      html += `<div class="hiq-chips-container">`;
      suggestions.forEach(s => {
        html += `
          <button type="button" class="hiq-chip-btn" data-question="${escapeHtml(s)}">
            <span>${escapeHtml(s)}</span>
            <i class="bi bi-arrow-right-short"></i>
          </button>
        `;
      });
      html += `</div>`;
    }

    html += `
        <span class="hiq-msg-time">${formatTime()}</span>
      </div>
    `;

    row.innerHTML = html;
    messagesBox.appendChild(row);

    // Bind chip clicks
    row.querySelectorAll('.hiq-chip-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const q = this.getAttribute('data-question');
        if (q) handleUserSend(q);
      });
    });

    scrollToBottom();
  }

  function showTyping() {
    const row = document.createElement('div');
    row.className = 'hiq-msg-row hiq-msg-bot';
    row.id = 'hiqAiTypingRow';
    row.innerHTML = `
      <div class="hiq-typing-indicator">
        <span class="hiq-typing-dot"></span>
        <span class="hiq-typing-dot"></span>
        <span class="hiq-typing-dot"></span>
      </div>
    `;
    messagesBox.appendChild(row);
    scrollToBottom();
  }

  function removeTyping() {
    const row = document.getElementById('hiqAiTypingRow');
    if (row) row.remove();
  }

  // Handle User Message Submission
  function handleUserSend(questionText) {
    const q = (questionText || (input ? input.value : '')).trim();
    if (!q || isSending) return;

    if (input) input.value = '';
    isSending = true;
    if (sendBtn) sendBtn.disabled = true;

    appendUserBubble(q);
    showTyping();

    // Call /api/chat
    const baseUrl = window.HOMEIQ_BASE_URL || '';
    const endpoint = baseUrl + '/api/chat';

    fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        message: q,
        page_context: pageContext
      })
    })
    .then(res => res.json())
    .then(data => {
      removeTyping();
      isSending = false;
      if (sendBtn) sendBtn.disabled = false;

      if (data && data.reply) {
        appendBotBubble(data.reply, data.suggestions, data.actions);
      } else {
        appendBotBubble(
          "I'm sorry, I encountered an issue retrieving that information. Please contact our support team at **+91 98765 43210** or submit a callback request.",
          ["Request a Callback", "Contact Customer Support"],
          [{ label: "Contact Support", url: baseUrl + "/contact" }]
        );
      }
    })
    .catch(err => {
      console.error('HOMEIQ Chatbot error:', err);
      removeTyping();
      isSending = false;
      if (sendBtn) sendBtn.disabled = false;

      appendBotBubble(
        "I'm temporarily having trouble connecting. You can reach our support team directly at **support@homeiq-security.com** or request a callback.",
        ["Request a Callback", "Explore HOMEIQ Products"],
        [{ label: "Request Callback", url: baseUrl + "/contact" }]
      );
    });
  }

  // Form Submit
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      handleUserSend();
    });
  }

  // Clear / Reset Conversation
  function resetConversation() {
    const baseUrl = window.HOMEIQ_BASE_URL || '';
    fetch(baseUrl + '/api/chat/reset', { method: 'POST' }).catch(() => {});

    messagesBox.innerHTML = '';
    initWelcome();
  }

  // Initialize Welcome Message and Contextual Suggestions
  function initWelcome() {
    messagesBox.innerHTML = '';

    // Fetch page-specific suggestions if available
    const baseUrl = window.HOMEIQ_BASE_URL || '';
    fetch(`${baseUrl}/api/chat/suggested?page=${encodeURIComponent(pageContext.page)}&product_slug=${encodeURIComponent(pageContext.product_slug)}`)
      .then(res => res.json())
      .then(data => {
        const suggestions = (data && data.suggestions && data.suggestions.length > 0) 
          ? data.suggestions 
          : DEFAULT_SUGGESTIONS;
        appendBotBubble(WELCOME_MESSAGE, suggestions, [
          { label: "Explore Products", url: baseUrl + "/shop" },
          { label: "Warranty Registration", url: baseUrl + "/warranty-registration" }
        ]);
      })
      .catch(() => {
        appendBotBubble(WELCOME_MESSAGE, DEFAULT_SUGGESTIONS, [
          { label: "Explore Products", url: baseUrl + "/shop" }
        ]);
      });
  }

  // Check if session was open before page refresh
  const wasOpen = sessionStorage.getItem('hiq_ai_chat_open');
  if (wasOpen === '1') {
    toggleChat(true);
  }

  initWelcome();
})();
</script>
