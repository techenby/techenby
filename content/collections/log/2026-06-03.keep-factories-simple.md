---
id: 705682a0-f4c1-469e-8912-cfc5f1c95a16
blueprint: tip
title: 'Keep Factories Simple'
tags:
  - development
  - laravel
updated_at: 1780516479
---
If you need to remove things after creating a model using a factory, it's doing too much. Convert it to a helper method instead. Having `afterCreating` in a `configure` method that creates other models, usually is a smell and slows down tests considerably. Bare factories should be the most barebones version of a model.
