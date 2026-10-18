# Stereo — Music Page

A PHP-powered music discovery and listening page with a vinyl hero, track discovery grid, featured audio player, and artist showcase.

## Requirements

- PHP 7.4+ (tested on PHP 8.0)
- Apache (XAMPP/WAMP) or the PHP built-in server

## Setup with XAMPP

1. Copy this folder to `C:\xampp\htdocs\music-page`
2. Start **Apache** from the XAMPP control panel
3. Open <http://localhost/music-page/>

## Run with PHP built-in server

```bash
php -S localhost:8000
```

Then open <http://localhost:8000/>

## Project structure

```
music-page/
├── index.php              # Entry point, renders all sections
├── includes/
│   ├── catalog.php        # Track & artist data
│   └── components.php     # Page section render functions
└── assets/
    ├── css/audio.css      # All styles
    └── js/audio.js        # Player logic, playlist, interactions
```

## Customizing

- Add/edit tracks and artists in `includes/catalog.php`
- Change markup of a section in `includes/components.php`
- Styles live in `assets/css/audio.css`, player behaviour in `assets/js/audio.js`
