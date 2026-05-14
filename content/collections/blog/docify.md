---
id: 16faf7f8-9c95-486c-add4-01ffd06c03f6
blueprint: blog
title: 'Introducing Docify: A simple markdown viewer for TALL stack applications'
header_image:
  - blog/banner-dark.png
  - blog/banner-light.png
tags:
  - package
  - development
  - laravel
  - livewire
updated_by: 2de7d260-84a8-4dfc-9a2e-af5aae989cd3
updated_at: 1778777965
content:
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: Background
  -
    type: paragraph
    content:
      -
        type: text
        text: 'One thing I love and hate about working at an agency is switching projects. I love changing things up, learning a new business, and discovering how folks solve different problems. One thing I find incredibly difficult is all the missing context. '
  -
    type: paragraph
    content:
      -
        type: text
        text: 'In the wave of "you don''t need comments," that was popular in tech spheres of social media a few years ago, less and less code I read has comments or the comment is self explanatory. For example:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          /**
           * Get the attributes that should be cast.
           *
           * @return array<string, string>
           */
          protected function casts(): array
          {
              return [
                  'email_verified_at' => 'datetime',
                  'password' => 'hashed',
              ];
          }
  -
    type: paragraph
    content:
      -
        type: text
        text: '"Get the attributes that should be cast" in my opinion is explained by the function name, and the comment does little to explain what casting means or why the '
      -
        type: text
        marks:
          -
            type: code
        text: email_verified_at
      -
        type: text
        text: ' or '
      -
        type: text
        marks:
          -
            type: code
        text: password
      -
        type: text
        text: ' are cast. In all of my applications I end up removing all but the type-hinting.'
  -
    type: paragraph
  -
    type: paragraph
    content:
      -
        type: text
        text: '// todo Talk about documenting '
      -
        type: text
        marks:
          -
            type: italic
        text: context
      -
        type: text
        text: ' and '
      -
        type: text
        marks:
          -
            type: italic
        text: decisions
  -
    type: paragraph
    content:
      -
        type: text
        text: '// todo Talk about not switching between browser and ide'
  -
    type: paragraph
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'The Goal'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The goal of Docify is:'
  -
    type: bulletList
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'co-locate documentation and context with the code'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'create a pleasurable reading experience'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'be lightweight and out of the way'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Why co-locate documentation with the code? '
  -
    type: orderedList
    attrs:
      start: 1
      type: null
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'It has a lower barrier to entry'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Written for developers by developers, no business crap getting in the way'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'Documentation can be added with pull requests, not as an after thought or a checkbox folks ignore or delete'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: "You're more likely to read it and update it. When I get a new ticket for editing existing code, I usually do a "
              -
                type: text
                marks:
                  -
                    type: code
                text: CMD+F
              -
                type: text
                text: ' to find where and how the class is used. Using '
              -
                type: text
                marks:
                  -
                    type: code
                text: CMD+F
              -
                type: text
                text: ' will now also pull up any related documentation.'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'More information for your AI agent'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Why create a pleasurable reading experience?'
  -
    type: orderedList
    attrs:
      start: 1
      type: null
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'More likely to be read'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: "Not everyone is using IDE's anymore"
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: "Non-developers, or folks who don't live in the code "
  -
    type: paragraph
  -
    type: heading
    attrs:
      level: 2
    content:
      -
        type: text
        text: 'Getting Started'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Under the hood, Docify is two blade files.'
  -
    type: orderedList
    attrs:
      start: 1
      type: null
    content:
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'a layout file that handles the sidebar and content areas'
      -
        type: listItem
        content:
          -
            type: paragraph
            content:
              -
                type: text
                text: 'a single-file Livewire component that handles configuring markdown extensions, converting the markdown to HTML and rendering the '
              -
                type: text
                marks:
                  -
                    type: code
                text: /docs
              -
                type: text
                text: ' page.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'To get started, install the package via composer.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: 'composer require techenby/docify'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Then run the install command, which creates a documentation folder and adds a starter markdown file.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: 'php artisan docify:install'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Optionally, a config file can be published to change the default folder, route, route name, and more.'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          return [
              'route' => '/docs',
              'route_name' => 'docs',
              'prefix' => 'docify',
              'folder' => './docs',
              'environments' => ['local'],
              'editor' => env('DOCIFY_EDITOR') ?: env('DEBUGBAR_EDITOR') ?: env('IGNITION_EDITOR', 'vscode'),
          ];
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Also, the layout and Livewire component can be published, which allows the layout to be modified to match your style and allows the markdown extensions to be configured. Personally, I like having these files in my apps '
      -
        type: text
        marks:
          -
            type: code
        text: resources/views/vendor
      -
        type: text
        text: " , so it's clearer that it's not necessarily a file for the app itself."
  -
    type: paragraph
  -
    type: paragraph
    content:
      -
        type: text
        text: '// todo talk about markdown extensions installed'
---
