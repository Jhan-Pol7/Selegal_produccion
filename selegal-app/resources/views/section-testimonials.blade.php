<div id="seccion-testimonials">
    <section class="page-intro">
        <div class="eyebrow">VOCES DE CONFIANZA</div>
        <{{ $sectionHeadingTag }}>Historias de <em>encuentro</em></{{ $sectionHeadingTag }}>
        <p>El diálogo abre la puerta a nuevas posibilidades.</p>
    </section>

    <section class="section grid three">
        @forelse($testimonials as $t)
            <article class="quote-card">
                <div class="quote-mark" aria-hidden="true">“</div>
                @if($t->example)
                    <small class="example">Contenido de ejemplo</small>
                @endif
                <blockquote>
                    <p>{{ $t->body }}</p>
                </blockquote>
                <div class="quote-author">
                    <span class="quote-avatar" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <circle cx="12" cy="8" r="3.5"/>
                            <path d="M5 20v-1.5C5 15.5 8 14 12 14s7 1.5 7 4.5V20"/>
                        </svg>
                    </span>
                    <b>{{ $t->name }}</b>
                </div>
            </article>
        @empty
            <p>Próximamente compartiremos testimonios.</p>
        @endforelse
    </section>
</div>
