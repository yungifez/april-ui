@php
$sections = [
    [
        'name' => 'header',
        'slot' => $header,
        'classes' => ['flex shrink-0 flex-col gap-2 p-2'],
    ],
    [
        'name' => 'content',
        'slot' => $content,
        'classes' => ['flex h-full min-h-0 flex-1 flex-col gap-2 overflow-auto group-data-[collapsible=icon]:[scrollbar-width:none]'],
    ],
    [
        'name' => 'footer',
        'slot' => $footer,
        'classes' => ['flex shrink-0 flex-col gap-2 p-2'],
    ],
];
@endphp

@foreach ($sections as $section)
    @continue($section['slot'] === null)
    <div data-sidebar="{{$section['name']}}" data-slot="sidebar-{{$section['name']}}"
        {{$section['slot']->attributes->twMerge($section['classes'])}}>
        {{$section['slot']}}
    </div>
@endforeach
