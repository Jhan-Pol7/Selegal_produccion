<div id="seccion-services">
    <section class="page-intro">
        <div class="eyebrow">LO QUE HACEMOS</div>
        <{{ $sectionHeadingTag }}>Nuestros <em>servicios</em></{{ $sectionHeadingTag }}>
        <p>Espacios de diálogo para construir soluciones duraderas.</p>
    </section>

    <section class="section grid two">
        <article class="service-card" id="civil">
            <div class="card-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                    <path d="M12 3v16m-4 2h8M4 7c3 0 5-2 8-2s5 2 8 2M6 7l-3 6a3 3 0 0 0 6 0L6 7Zm12 0-3 6a3 3 0 0 0 6 0l-3-6Z"/>
                </svg>
            </div>
            <h3>Materia civil</h3>
            <p>Acuerdos para resolver diferencias de carácter patrimonial.</p>
            <ul>
                <li>Indemnizaciones</li>
                <li>Desalojos</li>
                <li>Otorgamiento de escritura pública</li>
                <li>División y partición</li>
                <li>Liquidación de sociedad de gananciales</li>
            </ul>
            <a class="text-link" href="{{ route('request.form') }}">Solicitar conciliación →</a>
        </article>

        <article class="service-card" id="familiar">
            <div class="card-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                    <circle cx="9" cy="7" r="3"/>
                    <circle cx="17.5" cy="11.5" r="2.5"/>
                    <path d="M2.5 20v-1a6.5 6.5 0 0 1 9.6-5.7m1 6.7v-.5a4.4 4.4 0 0 1 8.8 0v.5"/>
                </svg>
            </div>
            <h3>Materia familiar</h3>
            <p>Diálogo para proteger el bienestar familiar.</p>
            <ul>
                <li>Pensión de alimentos</li>
                <li>Tenencia</li>
                <li>Régimen de visitas</li>
                <li>Reducción de alimentos</li>
            </ul>
            <a class="text-link" href="{{ route('request.form') }}">Solicitar conciliación →</a>
        </article>
    </section>
</div>
