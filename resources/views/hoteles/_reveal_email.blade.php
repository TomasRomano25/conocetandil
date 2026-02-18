{{--
    Reveal-email partial.
    Variables: $hotel (Hotel model), $emailId (unique string for this instance).
--}}
<div class="flex items-center gap-2 text-sm">
    <svg class="w-4 h-4 text-[#2D6A4F] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>

    {{-- Masked email + reveal button (shown by default) --}}
    <span id="{{ $emailId }}-row" class="flex items-center gap-2">
        <span class="text-gray-400 tracking-wide select-none" aria-hidden="true">
            ••••••@{{ Str::after($hotel->email, '@') }}
        </span>
        <button
            onclick="revealEmail('{{ $emailId }}', '{{ base64_encode($hotel->email) }}')"
            class="inline-flex items-center gap-1 text-xs font-semibold text-[#2D6A4F] bg-[#2D6A4F]/8 hover:bg-[#2D6A4F]/15 px-2 py-0.5 rounded-full transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver email
        </button>
    </span>

    {{-- Revealed email link (hidden initially) --}}
    <a id="{{ $emailId }}-link" href="#" class="text-[#2D6A4F] hover:underline hidden"></a>
</div>

@once
<script>
function revealEmail(id, encoded) {
    const email = atob(encoded);
    const row   = document.getElementById(id + '-row');
    const link  = document.getElementById(id + '-link');
    link.textContent = email;
    link.href        = 'mailto:' + email;
    row.classList.add('hidden');
    link.classList.remove('hidden');
}
</script>
@endonce
