@props(['role' => 'kol'])

<style>
    .cb-gradient { background: linear-gradient(135deg, #0b64d4, #1698f6); color: #ffffff; }
    .cb-bg-cream { background-color: #f8fafc; }
    .cb-bg-white { background-color: #ffffff; }
    .cb-text-dark { color: #071d49; }
    .cb-border-dark { border-color: rgba(7, 29, 73, 0.1); border-width: 1px; border-style: solid; }
    .cb-shadow-blue { box-shadow: 0 4px 14px rgba(11, 100, 212, 0.3); }
    .cb-bubble-bot { background-color: #ffffff; border: 1px solid rgba(7, 29, 73, 0.05); color: #071d49; }
    .cb-bubble-user { background: linear-gradient(135deg, #0b64d4, #1698f6); color: #ffffff; }
    .cb-quick-action { display: inline-block; padding: 0.5rem 1rem; background-color: #f8fafc; border: 1px solid #0b64d4; color: #0b64d4; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; transition: all 0.2s; text-decoration: none; margin-top: 0.75rem; }
    .cb-quick-action:hover { background-color: #0b64d4; color: #ffffff; }
    .cb-dot { background-color: #10b981; border: 2px solid #f8fafc; }
    .cb-ring-anim { animation: cb-ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite; border: 2px solid #0b64d4; }
    .cb-bounce { background-color: #0b64d4; animation: cb-bounce 1s infinite; }
    
    @keyframes cb-ping {
        75%, 100% { transform: scale(1.5); opacity: 0; }
    }
    @keyframes cb-bounce {
        0%, 100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0.8,0,1,1); }
        50% { transform: none; animation-timing-function: cubic-bezier(0,0,0.2,1); }
    }
    .cb-suggestion-card {
        background-color: #ffffff;
        border: 1px solid rgba(7, 29, 73, 0.1);
        border-radius: 0.875rem;
        padding: 0.625rem 0.75rem;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.625rem;
        width: 100%;
        text-align: left;
    }
    .cb-suggestion-card:hover {
        border-color: #0b64d4;
        background-color: #f0f7ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 100, 212, 0.08);
    }
    .cb-suggestion-card:focus-visible {
        outline: 2px solid #0b64d4;
        outline-offset: 2px;
    }
</style>

<div id="chatbot-container" class="fixed bottom-6 right-6 z-50 font-sans" data-role="{{ $role }}">
    {{-- Chat Window (Hidden by default) --}}
    <div id="chatbot-window" class="hidden absolute bottom-20 right-0 w-[350px] sm:w-[400px] h-[500px] max-h-[70vh] cb-bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden cb-border-dark transform transition-all origin-bottom-right duration-300 scale-95 opacity-0">
        {{-- Header --}}
        <div class="cb-gradient p-4 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i class="bi bi-robot text-xl"></i>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-sm leading-tight">Prabu AI</h4>
                    <p class="text-[10px] text-white/80">Online | Asisten Cerdas AI</p>
                </div>
            </div>
            <button id="chatbot-close" class="text-white/80 hover:text-white transition focus:outline-none" aria-label="Tutup Chat">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Chat Body --}}
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-4 cb-bg-cream space-y-4">
            {{-- Initial Greeting --}}
            <div class="flex gap-2 max-w-[85%]">
                <div class="w-6 h-6 rounded-full cb-gradient flex-shrink-0 flex items-center justify-center text-white mt-1">
                    <i class="bi bi-robot text-xs"></i>
                </div>
                <div class="cb-bubble-bot p-3 rounded-2xl rounded-tl-sm text-sm shadow-sm">
                    <p>Halo! Saya Prabu AI, asisten virtual Anda. Ada yang bisa saya bantu hari ini terkait dashboard Anda?</p>
                </div>
            </div>

            {{-- Suggested Questions (Cards Rekomendasi Pertanyaan) --}}
            <div id="chatbot-suggestions" class="pt-1 pb-1 space-y-2">
                <div class="flex items-center gap-1.5 px-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <i class="bi bi-stars text-amber-500"></i>
                    <span>Rekomendasi Pertanyaan</span>
                </div>
                
                <div class="grid grid-cols-1 gap-1.5">
                    @if ($role === 'kol')
                        <button type="button" 
                                data-prompt="Berapa total komisi saya bulan ini dan bagaimana status pencairannya?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Cek komisi bulan ini">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-wallet2"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Cek komisi bulan ini</div>
                                <div class="text-[10px] text-slate-500 truncate">Total komisi & status pencairan</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>

                        <button type="button" 
                                data-prompt="Bagaimana cara upload bukti tayang konten endorsement?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Cara upload bukti tayang">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-[#0b64d4] flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Cara upload bukti tayang</div>
                                <div class="text-[10px] text-slate-500 truncate">Panduan submit link video & insight</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>

                        <button type="button" 
                                data-prompt="Ada berapa tugas endorsement saya yang masih aktif atau pending?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Status endorsement & tugas aktif">
                            <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-clipboard-check"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Status endorsement & tugas</div>
                                <div class="text-[10px] text-slate-500 truncate">Cek kampanye berjalan & deadline</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>

                        <button type="button" 
                                data-prompt="Bagaimana cara memilih produk affiliate dan download materi di Bank Konten?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Cara memilih produk di Katalog">
                            <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-shop"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Pilih produk di Katalog</div>
                                <div class="text-[10px] text-slate-500 truncate">Materi promosi & komisi penjualan</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>
                    @else
                        <button type="button" 
                                data-prompt="Bagaimana status campaign aktif dan performa endorsement saya saat ini?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Status Campaign Aktif">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 text-[#0b64d4] flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-megaphone"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Status Campaign Aktif</div>
                                <div class="text-[10px] text-slate-500 truncate">Ringkasan kampanye & progress KOL</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>

                        <button type="button" 
                                data-prompt="Bagaimana cara mendaftarkan produk baru ke katalog kemitraan KERAJAAN?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Daftarkan Produk Baru">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-box-seam"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Daftarkan Produk Baru</div>
                                <div class="text-[10px] text-slate-500 truncate">Tambah stok, harga, & komisi kreator</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>

                        <button type="button" 
                                data-prompt="Bagaimana cara melihat dan mereview bukti tayang dari para kreator?" 
                                class="cb-suggestion-card group"
                                aria-label="Tanyakan: Review Bukti Tayang">
                            <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs shrink-0 group-hover:scale-105 transition-transform">
                                <i class="bi bi-check2-circle"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0c3685] text-xs leading-tight">Review Bukti Tayang</div>
                                <div class="text-[10px] text-slate-500 truncate">Verifikasi video & approve tayangan</div>
                            </div>
                            <i class="bi bi-arrow-right-short text-base text-slate-400 group-hover:text-[#0b64d4] group-hover:translate-x-0.5 transition-all"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Typing Indicator (Hidden) --}}
        <div id="chatbot-typing" class="hidden px-4 pb-2 cb-bg-cream">
            <div class="flex gap-2 max-w-[85%]">
                <div class="w-6 h-6 rounded-full cb-gradient flex-shrink-0 flex items-center justify-center text-white mt-1">
                    <i class="bi bi-robot text-xs"></i>
                </div>
                <div class="cb-bubble-bot px-4 py-3 rounded-2xl rounded-tl-sm shadow-sm flex items-center gap-1.5 w-16 h-10">
                    <div class="w-1.5 h-1.5 rounded-full cb-bounce" style="animation-delay: 0s"></div>
                    <div class="w-1.5 h-1.5 rounded-full cb-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-1.5 h-1.5 rounded-full cb-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-3 cb-bg-white cb-border-dark border-t" style="border-top-width: 1px;">
            <form id="chatbot-form" class="flex gap-2">
                <input type="text" id="chatbot-input" class="flex-1 cb-bg-cream cb-border-dark rounded-xl px-4 py-2 text-sm focus:outline-none transition cb-text-dark" placeholder="Ketik pertanyaan Anda..." autocomplete="off">
                <button type="submit" class="w-10 h-10 rounded-xl cb-gradient flex items-center justify-center flex-shrink-0 hover:brightness-105 transition focus:outline-none">
                    <i class="bi bi-send-fill text-sm ml-0.5"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Floating Action Button --}}
    <button id="chatbot-toggle" class="w-14 h-14 rounded-full cb-gradient flex items-center justify-center cb-shadow-blue hover:scale-105 transition-transform duration-200 focus:outline-none relative group">
        {{-- Notification dot --}}
        <span class="absolute top-0 right-0 w-3.5 h-3.5 cb-dot rounded-full"></span>
        {{-- Ripple effect --}}
        <span class="absolute inset-0 rounded-full cb-ring-anim opacity-20 group-[.active]:hidden"></span>
        
        <i class="bi bi-chat-dots-fill text-2xl group-[.active]:hidden"></i>
        <i class="bi bi-x-lg text-2xl hidden group-[.active]:block"></i>
    </button>
</div>

@push('scripts')
<script>
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
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
            }, 10);
            toggleBtn.classList.add('active');
            input?.focus();
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
            toggleBtn.classList.remove('active');
        }
    }

    toggleBtn?.addEventListener('click', toggleChat);
    closeBtn?.addEventListener('click', toggleChat);

    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    function appendMessage(msgRole, text) {
        const wrapper = document.createElement('div');
        wrapper.className = `flex gap-2 max-w-[85%] ${msgRole === 'user' ? 'ml-auto flex-row-reverse' : ''}`;
        
        let iconHtml = '';
        if (msgRole === 'assistant') {
            iconHtml = `
                <div class="w-6 h-6 rounded-full cb-gradient flex-shrink-0 flex items-center justify-center text-white mt-1">
                    <i class="bi bi-robot text-xs"></i>
                </div>
            `;
        }

        const actionRegex = /\[ACTION:(.+?)\|(.+?)\]/g;
        let parsedText = text.replace(actionRegex, (match, btnText, url) => {
            return `<div><a href="${url}" class="cb-quick-action">${btnText}</a></div>`;
        });
        
        parsedText = parsedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        parsedText = parsedText.replace(/\n/g, '<br>');

        const bubbleClasses = msgRole === 'user' 
            ? 'cb-bubble-user rounded-2xl rounded-tr-sm'
            : 'cb-bubble-bot rounded-2xl rounded-tl-sm';

        wrapper.innerHTML = `
            ${iconHtml}
            <div class="${bubbleClasses} p-3 text-sm shadow-sm space-y-2">
                <div>${parsedText}</div>
            </div>
        `;

        messagesContainer?.appendChild(wrapper);
        scrollToBottom();
    }

    const suggestionsContainer = document.getElementById('chatbot-suggestions');
    const suggestionBtns = document.querySelectorAll('.cb-suggestion-card');

    async function handleSendMessage(rawText) {
        const message = (rawText || '').trim();
        if (!message) return;

        // Hide recommendation cards once interaction begins
        if (suggestionsContainer) {
            suggestionsContainer.classList.add('hidden');
        }

        appendMessage('user', message);
        chatHistory.push({ role: 'user', content: message });
        if (input) input.value = '';
        
        typingIndicator?.classList.remove('hidden');
        scrollToBottom();

        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]');
        const csrfToken = csrfTokenMeta ? (csrfTokenMeta.content || csrfTokenMeta.value) : '';

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
            
            typingIndicator?.classList.add('hidden');
            
            if (response.ok && data.success) {
                appendMessage('assistant', data.reply);
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                appendMessage('assistant', data.reply || 'Maaf, terjadi kesalahan saat memproses permintaan.');
            }
        } catch (error) {
            console.error('Chatbot Error:', error);
            typingIndicator?.classList.add('hidden');
            appendMessage('assistant', 'Maaf, gagal terhubung ke server. Silakan coba lagi.');
        }
    }

    suggestionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const prompt = btn.getAttribute('data-prompt');
            if (prompt) {
                handleSendMessage(prompt);
            }
        });
    });

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        handleSendMessage(input?.value);
    });
});
</script>
@endpush
