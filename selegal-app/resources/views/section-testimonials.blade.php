<div id="seccion-testimonials">
<section class="page-intro"><div class="eyebrow">VOCES DE CONFIANZA</div><h1>Historias de <em>encuentro</em></h1><p>El diálogo abre la puerta a nuevas posibilidades.</p></section><section class="section grid three">@forelse($testimonials as $t)<article class="quote-card"><div class="quote-mark">“</div>@if($t->example)<small class="example">Contenido de ejemplo</small>@endif<p>{{ $t->body }}</p><b>{{ $t->name }}</b></article>@empty<p>Próximamente compartiremos testimonios.</p>@endforelse</section>
</div>
