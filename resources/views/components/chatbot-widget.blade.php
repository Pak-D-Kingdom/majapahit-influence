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
                    <p class="text-[10px] text-white/80">Online | AI-Powered Guide</p>
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
    @vite('resources/js/chatbot.js')
@endpush
