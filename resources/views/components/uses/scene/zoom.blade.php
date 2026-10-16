@props(['scene', 'activeScene' => 'desk'])

<x-uses.scene-frame
    :$scene
    :$activeScene
    :heading="$scene->title"
    type="Closer look"
    description="Hover or tap a dot to see what it is."
>
    <s:collection:uses :scene:is="$scene->id" scope="item">
        <x-uses.hotspot :$item />
    </s:collection:uses>
</x-uses.scene-frame>
