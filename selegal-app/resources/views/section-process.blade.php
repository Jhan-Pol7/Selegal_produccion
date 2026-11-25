<div id="seccion-process">
    <section class="page-intro">
        <div class="eyebrow">PASO A PASO</div>
        <{{ $sectionHeadingTag }}>El camino al <em>acuerdo</em></{{ $sectionHeadingTag }}>
        <p>Un proceso claro, cercano y centrado en el diálogo.</p>
    </section>

    <section class="section narrow process-section">
        <ol class="timeline" role="list">
            @foreach(['Solicitud'=>'Cuéntanos brevemente tu caso.','Registro'=>'Registramos tu solicitud y revisamos la información.','Citación'=>'Se invita a las partes a participar.','Audiencia'=>'Las partes dialogan con la asistencia de un conciliador.','Resultado'=>'El resultado se recoge en el acta correspondiente.'] as $title=>$description)
                <li class="step">
                    <span aria-hidden="true">{{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>
                    <div>
                        <h3>{{ $title }}</h3>
                        <p>{{ $description }}</p>
                    </div>
                </li>
            @endforeach
        </ol>

        <div class="process-preparation">
            <h3>Antes de comenzar</h3>
            <div class="process-details">
                <div class="info-box">
                    <p><b>Duración estimada:</b> desde 2 o 3 días; casi inmediato si ambas partes ya tienen un acuerdo.</p>
                </div>
                <div class="info-box">
                    <p><b>Requisitos:</b> DNI de ambas partes. Si solo una parte solicita, debe acudir al centro a presentar la solicitud.</p>
                </div>
            </div>
            <a class="btn btn-green" href="{{ route('request.form') }}">Solicitar conciliación →</a>
        </div>
    </section>
</div>
