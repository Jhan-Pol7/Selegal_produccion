<div id="seccion-contact">
    <section class="page-intro">
        <div class="eyebrow">ESTAMOS CERCA</div>
        <{{ $sectionHeadingTag }}>Conversemos <em>contigo</em></{{ $sectionHeadingTag }}>
        <p>Estamos aquí para orientarte en el primer paso.</p>
    </section>

    <section class="section">
        <div class="grid two contact-cards">
            <div class="contact-line">
                <span class="contact-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                        <path d="m8.7 4.6 2 4-2.2 2a13 13 0 0 0 5 5l2-2.2 4 2c.7.3 1 1 .7 1.7l-.7 1.8c-.4.9-1.3 1.4-2.2 1.3A15.6 15.6 0 0 1 3.8 6.9C3.7 6 4.2 5.1 5 4.7l1.8-.8c.7-.3 1.5 0 1.9.7Z"/>
                    </svg>
                </span>
                <div>
                    <b>Teléfono / WhatsApp</b>
                    <p><a href="https://wa.me/51{{ preg_replace('/\D/','',$phone) }}">{{ $phone }}</a></p>
                </div>
            </div>

            <div class="contact-line">
                <span class="contact-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                        <rect x="3" y="5.5" width="18" height="13" rx="2"/>
                        <path d="m3.8 7.5 7 5a2 2 0 0 0 2.4 0l7-5"/>
                    </svg>
                </span>
                <div>
                    <b>Correo electrónico</b>
                    <p><a href="mailto:{{ $settings['email']??'jhanpool799@gmail.com' }}">{{ $settings['email']??'jhanpool799@gmail.com' }}</a></p>
                </div>
            </div>

            <div class="contact-line">
                <span class="contact-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                        <circle cx="6" cy="12" r="2.5"/>
                        <circle cx="17.5" cy="6" r="2.5"/>
                        <circle cx="17.5" cy="18" r="2.5"/>
                        <path d="m8.2 10.9 7.1-3.8m-7.1 6 7.1 3.8"/>
                    </svg>
                </span>
                <p>Facebook · Instagram · TikTok</p>
            </div>

            @if(($settings['show_address']??'0')==='1' && !empty($settings['address']))
                <div class="contact-line">
                    <span class="contact-icon" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M19 10c0 4.8-7 10-7 10s-7-5.2-7-10a7 7 0 1 1 14 0Z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                    </span>
                    <div>
                        <b>Dirección</b>
                        <p>{{ $settings['address'] }}</p>
                    </div>
                </div>
            @else
                <div class="contact-status">
                    <strong></strong>
                    <small></small>
                </div>
            @endif
        </div>
    </section>
</div>
