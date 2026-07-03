<section class="max-w-7xl mx-auto px-4 sm:px-6 py-14">

    <div class="flex items-end justify-between mb-6 sm:mb-8 pb-4 sm:pb-5 border-b border-stone-200">
        <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-stone-900 leading-tight">
            Apa Kata
            <span class="block italic text-amber-700/80 text-[0.9em]">Pembeli</span>
        </h2>
        <span class="hidden sm:block text-[10px] sm:text-[11px] uppercase tracking-widest text-stone-400 font-medium">Ulasan</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">

        @forelse($testimonials as $testimonial)

        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md transition duration-300 p-5 flex flex-col gap-4">

            <div class="text-amber-200 font-serif text-5xl leading-none select-none">"</div>

            <p class="text-sm text-stone-600 leading-relaxed -mt-4">
                {{ $testimonial->message }}
            </p>

            <div class="flex items-center justify-between mt-auto pt-3 border-t border-stone-100">
                <div>
                    <p class="text-sm font-semibold text-stone-800">
                        {{ $testimonial->user->name }}
                    </p>
                    <p class="text-[10px] text-stone-400 mt-0.5">
                        {{ $testimonial->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-3.5 h-3.5 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-stone-200' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
            </div>

        </div>

        @empty

        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
            <div class="w-14 h-14 rounded-2xl bg-stone-100 flex items-center justify-center text-2xl mb-3">
                💬
            </div>
            <p class="font-serif text-base font-bold text-stone-700 mb-1">Belum ada ulasan</p>
            <p class="text-xs text-stone-400">Jadilah yang pertama berbelanja dan memberikan ulasan.</p>
        </div>

        @endforelse

    </div>

</section>