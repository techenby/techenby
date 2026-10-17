@props(['scene', 'activeScene' => 'desk'])

<x-uses.scene-frame
    :$scene
    :$activeScene
    heading="Have a look around"
    description="Hover or tap a dot to see what it is. Yellow dots zoom in for a closer look, and the computer opens my desktop."
>
    <s:collection:uses :scene:is="$scene->id" scope="item">
        <x-uses.hotspot :$item />
    </s:collection:uses>
</x-uses.scene-frame>
