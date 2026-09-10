document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('chatbot-container');
    const toggleBtn = document.getElementById('chatbot-toggle');
    const closeBtn = document.getElementById('chatbot-close');
    const chatWindow = document.getElementById('chatbot-window');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messagesContainer = document.getElementById('chatbot-messages');
    const typingIndicator = document.getElementById('chatbot-typing');

    if (!container) return;

    const role = container.dataset.role;
    let chatHistory = [];
    
    // Toggle Chat Window
    function toggleChat() {
        const isHidden = chatWindow.classList.contains('hidden');
        if (isHidden) {
            chatWindow.classList.remove('hidden');
            // Small delay to allow display:block to apply before animating opacity/scale
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
            }, 10);
            toggleBtn.classList.add('active');
            input.focus();
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300); // match duration-300
            toggleBtn.classList.remove('active');
        }
    }

    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    // Scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Append Message to UI
    function appendMessage(role, text) {
        const wrapper = document.createElement('div');
        wrapper.className = `flex gap-2 max-w-[85%] ${role === 'user' ? 'ml-auto flex-row-reverse' : ''}`;
        
        let iconHtml = '';
        if (role === 'assistant') {
            iconHtml = `
                <div class="w-6 h-6 rounded-full cb-gradient flex-shrink-0 flex items-center justify-center text-white mt-1">
                    <i class="bi bi-robot text-xs"></i>
                </div>
            `;
        }

        // Parse Quick Actions: [ACTION:Text|/url]
        const actionRegex = /\[ACTION:(.+?)\|(.+?)\]/g;
        let parsedText = text.replace(actionRegex, (match, btnText, url) => {
            return `<div><a href="${url}" class="cb-quick-action">${btnText}</a></div>`;
        });
        
        // Basic Markdown to HTML (bold and line breaks)
        parsedText = parsedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        parsedText = parsedText.replace(/\n/g, '<br>');

        const bubbleClasses = role === 'user' 
            ? 'cb-bubble-user rounded-2xl rounded-tr-sm'
            : 'cb-bubble-bot rounded-2xl rounded-tl-sm';

        wrapper.innerHTML = `
            ${iconHtml}
            <div class="${bubbleClasses} p-3 text-sm shadow-sm space-y-2">
                <div>${parsedText}</div>
            </div>
        `;

        messagesContainer.appendChild(wrapper);
        scrollToBottom();
    }

    // Handle Form Submit
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = input.value.trim();
        if (!message) return;

        // Add User Message
        appendMessage('user', message);
        chatHistory.push({ role: 'user', content: message });
        input.value = '';
        
        // Show Typing
        typingIndicator.classList.remove('hidden');
        scrollToBottom();

        // Fetch CSRF Token
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : '';

        try {
            const response = await fetch('/chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: message,
                    role: role,
                    history: chatHistory
                })
            });

            const data = await response.json();
            
            typingIndicator.classList.add('hidden');
            
            if (response.ok && data.success) {
                appendMessage('assistant', data.reply);
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                appendMessage('assistant', data.reply || 'Maaf, terjadi kesalahan saat memproses permintaan.');
            }
        } catch (error) {
            console.error('Chatbot Error:', error);
            typingIndicator.classList.add('hidden');
            appendMessage('assistant', 'Maaf, gagal terhubung ke server. Silakan coba lagi.');
        }
    });
});
