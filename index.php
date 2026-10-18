<?php
require __DIR__ . '/includes/catalog.php';
require __DIR__ . '/includes/components.php';
$tracksJson = json_encode($tracks, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f2eee7">
  <meta name="description" content="Find your next favorite track on Stereo — a music discovery and listening experience.">
  <title>Stereo — Listen closer</title>
  <link rel="preconnect" href="https://images.unsplash.com">
  <link rel="stylesheet" href="assets/css/audio.css">
</head>
<body>
  <main>
    <?php VinylHero($tracks); ?>
    <?php MusicDiscovery($tracks); ?>
    <?php FeaturedAudioPlayer(); ?>
    <?php ArtistShowcase($artists); ?>
  </main>
  <audio id="global-audio" preload="none" aria-label="Music playback"></audio>
  <div class="sr-only" id="live-status" aria-live="polite"></div>
  <script>window.STEREO_TRACKS = <?= $tracksJson ?>;</script>
  <script src="assets/js/audio.js" defer></script>
</body>
</html>
