<div id="seccion-team">
    <section class="page-intro">
        <div class="eyebrow">PERSONAS QUE ESCUCHAN</div>
        <{{ $sectionHeadingTag }}>Nuestro <em>equipo</em></{{ $sectionHeadingTag }}>
        <p>Profesionales comprometidos con el diálogo y el respeto.</p>
    </section>

    <section class="section">
        <div class="grid three">
            @foreach($members as $member)
                <article class="person-card">
                    @if($member->photo)
                        <img class="person-photo" src="{{ asset('uploads/'.$member->photo) }}" alt="" width="96" height="96" loading="lazy" decoding="async">
                    @else
                        <div class="person-placeholder" aria-hidden="true">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M5 20v-1.5C5 15.5 8 14 12 14s7 1.5 7 4.5V20"/>
                            </svg>
                        </div>
                    @endif
                    <div class="person-body">
                        @if($member->example)
                            <small class="example">Contenido de ejemplo</small>
                        @endif
                        <h3>{{ $member->name }}</h3>
                        <p>{{ $member->role }}</p>
                        @if($member->description)
                            <p>{{ $member->description }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
            <article class="person-card coming">
                <span class="person-placeholder" aria-hidden="true">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                        <path d="M12 3c.8 5.7 3.3 8.2 9 9-5.7.8-8.2 3.3-9 9-.8-5.7-3.3-8.2-9-9 5.7-.8 8.2-3.3 9-9Z"/>
                    </svg>
                </span>
                <h3>Próximamente más integrantes</h3>
            </article>
        </div>
    </section>
</div>
