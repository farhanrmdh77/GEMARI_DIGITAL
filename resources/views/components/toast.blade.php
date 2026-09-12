<!-- Global Toast Notification -->
@if(session('success') || session('error'))
<div x-data="{ 
        show: false, 
        progress: 100, 
        type: '{{ session('success') ? 'success' : 'error' }}',
        message: '{{ session('success') ?? session('error') }}'
    }" 
    x-init="
        setTimeout(() => { show = true; }, 50);
        let startTime = Date.now();
        let duration = {{ session('duration', 3000) }};
        let timer = setInterval(() => {
            let elapsed = Date.now() - startTime;
            progress = Math.max(0, 100 - (elapsed / duration * 100));
            if(elapsed >= duration) {
                clearInterval(timer);
                show = false;
            }
        }, 16);
    "
    x-show="show" 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed top-20 right-4 sm:right-8 z-[100] w-[320px] bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-100 overflow-hidden flex flex-col"
    style="display: none;"
>
    <!-- Content -->
    <div class="p-4 flex items-start gap-3.5">
        <!-- Icon -->
        <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center shadow-sm" 
             :class="type === 'success' ? 'bg-[#20c997]' : 'bg-red-500'">
            <!-- Check Icon -->
            <svg x-show="type === 'success'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            <!-- Error Icon -->
            <svg x-show="type === 'error'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        
        <!-- Text -->
        <div class="flex-1 min-w-0 mt-0.5">
            <h4 class="text-[13px] font-bold font-geist text-slate-800 tracking-wider" x-text="type === 'success' ? 'SUKSES!' : 'GAGAL!'"></h4>
            <p class="text-[12px] text-slate-500 font-inter mt-1 leading-relaxed pr-2" x-text="message"></p>
        </div>

        <!-- Close Button -->
        <button @click="show = false" class="shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-1 -mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Progress Bar -->
    <div class="h-1 bg-slate-50 w-full mt-auto">
        <div class="h-full transition-all duration-75 ease-linear" 
             :class="type === 'success' ? 'bg-[#20c997]' : 'bg-red-500'"
             :style="'width: ' + progress + '%'"></div>
    </div>
</div>
@endif
