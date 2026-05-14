@php
    $anchorIcons = filter_var($anchorIcons ?? true, FILTER_VALIDATE_BOOLEAN);
    $contact = \App\Models\Page::payloadFor(\App\Models\Page::KEY_CONTACT_PAGE);
    $social = is_array($contact['social'] ?? null) ? $contact['social'] : [];
    $ig = is_array($social['instagram'] ?? null) ? $social['instagram'] : [];
    $igUrl = (string) ($ig['url'] ?? '');
    $facebookUrl = (string) ($social['facebook_url'] ?? '');
    $linkedinUrl = (string) ($social['linkedin_url'] ?? '');
    $youtubeUrl = (string) ($social['youtube_url'] ?? '');

    $items = [
        ['url' => $facebookUrl, 'icon' => 'bi-facebook', 'label' => 'Facebook'],
        ['url' => $igUrl, 'icon' => 'bi-instagram', 'label' => 'Instagram'],
        ['url' => $linkedinUrl, 'icon' => 'bi-linkedin', 'label' => 'LinkedIn'],
        ['url' => $youtubeUrl, 'icon' => 'bi-youtube', 'label' => 'YouTube'],
    ];
@endphp
@foreach ($items as $item)
    @continue(! filled($item['url']))
    @if ($anchorIcons)
        <li>
            <a
                href="{{ $item['url'] }}"
                class="bi {{ $item['icon'] }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="{{ $item['label'] }}"
            ></a>
        </li>
    @else
        <li>
            <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $item['label'] }}">
                <i class="bi {{ $item['icon'] }}"></i>
            </a>
        </li>
    @endif
@endforeach
