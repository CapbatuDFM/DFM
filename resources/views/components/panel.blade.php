@props(['title', 'subtitle' => null, 'href' => null, 'action' => 'View all'])
<section {{ $attributes->class('section') }}>
    <header class="section-head">
        <div>
            <h2>{{ $title }}</h2>
            @if ($subtitle)
                <p class="sub">{{ $subtitle }}</p>
            @endif
        </div>
        @if ($href)
            <a href="{{ $href }}" class="link">{{ $action }}</a>
        @endif
    </header>
    {{ $slot }}
</section>
