@props(['label', 'value', 'hint' => null, 'tone' => null])
{{-- One figure in a <dl class="figures">. tone="alert" colours the value. --}}
<div {{ $attributes->class(['figure', 'figure--alert' => $tone === 'alert']) }}>
    <dt>{{ $label }}</dt>
    <dd class="value">{{ $value }}</dd>
    @if ($hint)
        <dd class="hint">{{ $hint }}</dd>
    @endif
</div>
