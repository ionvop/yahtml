# YAML as HTML

YAML as HTML (YAHTML) is a lightweight PHP-based system that dynamically renders HTML pages using YAML configurations. It reads YAML files to generate structured HTML, apply styles, and include JavaScript files.

## Features

- **Dynamic HTML Rendering:** Uses structured YAML files to generate HTML elements.
- **Scoped and Global Styles:** Supports per-route and global CSS styles via YAML.
- **JavaScript Inclusion:** Loads both global and per-route JavaScript files.
- **Modular Imports:** Allows importing YAML-defined content from other files.

## Project Structure

```
/project-root
│── /routes
│   ├── /example-page
│   │   ├── index.yaml      # Defines the page content
│   │   ├── style.yaml      # (Optional) Defines styles for this page
│   │   ├── script.js       # (Optional) Defines JavaScript for this page
│── style.yaml              # (Optional) Global styles
│── script.js               # (Optional) Global JavaScript
│── index.php               # Main PHP script
```

## YAML Structure

### 1. **Content Definition (`index.yaml`)**
The content file is structured hierarchically, defining HTML elements as keys.

```yaml
div:
  class: container
  _: 
    - h1: Welcome to My Page
    - p:
      class: description
      _: This is a dynamically generated page using YAML.
    - import: components/button.yaml
```
- `_` (underscore) represents the inner content of an HTML tag.
- `import` allows including external YAML content.

### 2. **Style Definition (`style.yaml`)**
CSS styles can be defined in YAML.

```yaml
body:
  background-color: "#f4f4f4"

.container:
  width: 80%
  margin: 0 auto
```
- Pipe (`|`) replaces colons (`:`) for pseudo-classes (`hover`, `focus`):
  ```yaml
  a|hover:
    color: red
  ```

### 3. **JavaScript Inclusion (`script.js`)**
A standard JavaScript file is used:

```js
document.addEventListener("DOMContentLoaded", function() {
    console.log("Page loaded!");
});
```

## How It Works

1. The script extracts the request URI and finds the corresponding YAML file.
2. It parses the YAML structure and renders it into HTML.
3. If styles and scripts exist, they are included in the page.

## Requirements

- PHP 7+ with YAML extension (`php-yaml`)
- Web server (Apache, Nginx, or PHP built-in server)

## Usage

Run a local PHP server:

```sh
php -S localhost:8000
```

Then, visit `http://localhost:8000/example-page/` in your browser.