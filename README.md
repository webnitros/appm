## TemplateApp

```bash
gh release create "v0.0.8" --generate-notes
```

## Подключения в composer.json

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/webnitros/appm"
    }
  ],
  "require": {
    "webnitros/appm": "^1.0.0"
  }
}
```

# Подключение в проект

В проект подключить файл bootstrap/app.php
