@if (session('contact_success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 font-medium text-sm">
        ✓ Tu mensaje fue enviado correctamente. El hotel se pondrá en contacto pronto.
    </div>
@endif

<form id="hotel-contact-form" action="{{ route('hoteles.contact', $hotel) }}" method="POST" class="bg-gray-50 rounded-2xl p-6 space-y-4">
    @csrf
    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-hotel-contact">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">Tu nombre *</label>
            <input type="text" name="name" id="contact_name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" name="email" id="contact_email" value="{{ old('email') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono (opcional)</label>
        <input type="text" name="phone" id="contact_phone" value="{{ old('phone') }}"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">
    </div>
    <div>
        <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje *</label>
        <textarea name="message" id="contact_message" rows="4" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#52B788]">{{ old('message') }}</textarea>
        @error('message') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    @error('captcha') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
    <button type="submit"
        class="w-full bg-[#2D6A4F] hover:bg-[#1A1A1A] text-white font-semibold py-3 rounded-xl transition text-sm">
        Enviar mensaje
    </button>
</form>
@if(\App\Models\Configuration::get('recaptcha_site_key'))
<script>
document.getElementById('hotel-contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ \App\Models\Configuration::get("recaptcha_site_key") }}', {action: 'hotel_contact'}).then(function(token) {
            document.getElementById('g-recaptcha-hotel-contact').value = token;
            form.submit();
        });
    });
});
</script>
@endif
