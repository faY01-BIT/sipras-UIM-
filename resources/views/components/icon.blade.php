@props(['name', 'class' => '', 'size' => 16])
@php
$icons = [
    'layout-dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
    'category' => '<rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="8" rx="1"/><rect x="3" y="13" width="8" height="8" rx="1"/><rect x="13" y="13" width="8" height="8" rx="1"/>',
    'box' => '<path d="M3 8l9-5 9 5-9 5-9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
    'clipboard-list' => '<rect x="6" y="3" width="12" height="18" rx="2"/><path d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V3z"/><path d="M9 10h6M9 14h6M9 18h3"/>',
    'rotate' => '<path d="M4 12a8 8 0 0 1 14.5-4.6M20 12a8 8 0 0 1-14.5 4.6"/><path d="M18.5 3v4.4h-4.4M5.5 21v-4.4h4.4"/>',
    'file-report' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><path d="M9 13h6M9 17h4"/>',
    'tool' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    'alert-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
    'arrow-right' => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
    'circle-check' => '<circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/>',
    'clipboard-plus' => '<rect x="6" y="3" width="12" height="18" rx="2"/><path d="M9 3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V3z"/><path d="M12 11v6M9 14h6"/>',
    'flame' => '<path d="M12 2c1 3-2 4-2 7a4 4 0 0 0 8 0c0-1-.5-2-1-3 2 1 3 3 3 5a6 6 0 0 1-12 0c0-4 2-5 4-9z"/>',
    'key' => '<circle cx="8" cy="15" r="4"/><path d="M10.85 12.15L19 4M17 6l2 2M14 9l2 2"/>',
    'lock-question' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/><path d="M12 15.5a1.5 1.5 0 1 1 1.5-1.8" /><path d="M12 18h.01"/>',
    'logout' => '<path d="M9 12h11M17 8l4 4-4 4"/><path d="M9 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3"/>',
    'mail-check' => '<path d="M3 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-7"/><path d="M3 6l9 7 9-7"/><path d="M15 19l2 2 4-4"/>',
    'pencil' => '<path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>',
    'plus' => '<path d="M12 5v14M5 12h14"/>',
    'refresh' => '<path d="M4 12a8 8 0 0 1 14.5-4.6M20 12a8 8 0 0 1-14.5 4.6"/><path d="M18.5 3v4.4h-4.4M5.5 21v-4.4h4.4"/>',
    'send' => '<path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/>',
    'trash' => '<path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="M6 7l1 13a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-13"/><path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3"/>',
    'download' => '<path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/>',
    'file-type-pdf' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><text x="12" y="18" font-family="Arial" font-size="6" font-weight="700" text-anchor="middle" fill="currentColor" stroke="none">PDF</text>',
    'file-spreadsheet' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h8M11 13v7"/>',
];
$path = $icons[$name] ?? '';
@endphp
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block shrink-0 {{ $class }}">{!! $path !!}</svg>
