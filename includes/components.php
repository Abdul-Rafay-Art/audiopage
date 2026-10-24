<?php
function SiteHeader() { ?>
<header class="site-header">
  <a class="brand" href="#top" aria-label="Aside home">ASIDE</a>
  <span class="header-wordmark" aria-hidden="true">vinylist</span>
  <nav class="main-nav" aria-label="Main navigation"><a href="#top">Home</a><a href="#player">Music</a><a href="#albums">Albums</a><a href="#artists">Artists</a><a href="#discover">Playlists</a><a href="#about">About</a></nav>
  <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false"><span></span><span></span></button>
</header>
<?php }

function FeaturedCollections($tracks) { ?>
<section class="featured-collections" id="albums">
  <div class="collections-heading"><div><p class="eyebrow">POPULAR ALBUMS</p><h2>Featured<br>Collections</h2></div><p>Timeless albums.<br>Always a good idea.</p><a href="#discover">View All&nbsp; →</a></div>
  <div class="collection-grid"><?php foreach (array_slice($tracks, 0, 5) as $i => $track) { ?>
    <button class="collection-card" type="button" data-play-track="<?= htmlspecialchars($track['id']) ?>"><span class="collection-art"><img src="<?= htmlspecialchars($track['coverUrl']) ?>" alt="<?= htmlspecialchars($track['album']) ?> album art" loading="lazy"><i>▶</i></span><strong><?= htmlspecialchars($track['album']) ?><?= $i > 0 ? ' · '.htmlspecialchars($track['title']) : '' ?></strong><small><?= htmlspecialchars($track['artist']) ?></small></button>
  <?php } ?></div>
</section>
<?php }

function VinylHero($tracks) { ?>
<section class="hero" id="top">
  <div class="hero-layout">
    <div class="hero-copy">
      <p class="eyebrow">NOW PLAYING</p>
      <h1>Album:<br>Mothership</h1>
      <p class="hero-release">2025 · Led Zeppelin</p>
      <p class="hero-intro">A collection of records for late nights, open roads and everything in between. Drop the needle and stay awhile.</p>
      <div class="hero-actions"><button class="hero-play" type="button" data-play-track="<?= htmlspecialchars($tracks[0]['id']) ?>">▶ &nbsp; Play Album</button><button class="hero-round" type="button" aria-label="Like album">♡</button><button class="hero-round" type="button" aria-label="More album options">···</button></div>
      <div class="hero-progress"><span></span></div><div class="hero-time"><span>00:42</span><span>03:58</span></div>
    </div>
    <?php VinylPlayer(); ?>
    <aside class="hero-album-info" aria-label="Featured album">
      <img class="hero-album-cover" src="<?= htmlspecialchars($tracks[0]['coverUrl']) ?>" alt="<?= htmlspecialchars($tracks[0]['album']) ?> artwork">
      <div class="album-info-top"><small>LAST SPINNED</small></div>
      <h2><?= htmlspecialchars($tracks[0]['album']) ?></h2>
      <p class="album-release">2025 &nbsp;·&nbsp; <?= htmlspecialchars($tracks[0]['artist']) ?></p>
      <div class="tracklist-heading"><span>TRACKS</span></div>
      <?php FeaturedTrackList($tracks); ?>
      <a class="text-link" href="#discover">VIEW FULL ALBUM&nbsp; →</a>
    </aside>
  </div>
</section>
<?php }

function VinylPlayer() { ?>
<div class="vinyl-wrap" role="img" aria-label="Black vinyl record with a silver curved tonearm">
  <div class="vinyl-disc" aria-hidden="true">
    <svg class="vinyl-grooves" viewBox="0 0 500 500" fill="none">
      <defs>
        <radialGradient id="vinyl-edge">
          <stop offset="0.9" stop-color="#060708"/>
          <stop offset="0.975" stop-color="#101213"/>
          <stop offset="0.993" stop-color="#292b2b"/>
          <stop offset="1" stop-color="#080909"/>
        </radialGradient>
      </defs>
      <circle cx="250" cy="250" r="249" fill="url(#vinyl-edge)"/>
      <?php for ($ring = 83; $ring < 242; $ring += 2.6) { ?>
        <circle cx="250" cy="250" r="<?= $ring ?>" stroke="#a5a9a7" stroke-width=".65" opacity="<?= $ring > 233 ? '.045' : '.18' ?>"/>
      <?php } ?>
      <g stroke="#000" stroke-width="2" opacity=".65">
        <circle cx="250" cy="250" r="121"/>
        <circle cx="250" cy="250" r="164"/>
        <circle cx="250" cy="250" r="205"/>
      </g>
      <circle cx="250" cy="250" r="79" stroke="#3a3d3c" stroke-width="1" opacity=".35"/>
    </svg>
    <div class="vinyl-label">
      <svg class="vinyl-emblem" viewBox="0 0 100 100" fill="none">
        <circle cx="50" cy="50" r="46" stroke="#303332" stroke-width="7"/>
        <g stroke="#606360" stroke-width="3.5" stroke-linejoin="round" opacity=".8">
          <path d="M50 12 C38 28 35 43 43 51 C51 59 62 48 56 37 C50 27 36 29 28 39 C20 49 21 62 26 70"/>
          <path d="M50 12 C38 28 35 43 43 51 C51 59 62 48 56 37 C50 27 36 29 28 39 C20 49 21 62 26 70" transform="rotate(120 50 50)"/>
          <path d="M50 12 C38 28 35 43 43 51 C51 59 62 48 56 37 C50 27 36 29 28 39 C20 49 21 62 26 70" transform="rotate(240 50 50)"/>
        </g>
        <circle cx="50" cy="50" r="4" fill="#090a0a" stroke="#777975" stroke-width="1.5"/>
      </svg>
    </div>
  </div>
  <svg class="vinyl-tonearm" viewBox="0 0 300 375" fill="none" aria-hidden="true">
    <defs>
      <linearGradient id="tonearm-metal" x1="148" y1="0" x2="218" y2="0" gradientUnits="userSpaceOnUse">
        <stop stop-color="#333737"/>
        <stop offset=".26" stop-color="#8b8d89"/>
        <stop offset=".5" stop-color="#f3f1e8"/>
        <stop offset=".7" stop-color="#b4b6b1"/>
        <stop offset="1" stop-color="#454949"/>
      </linearGradient>
      <linearGradient id="headshell-metal" x1="-10" y1="0" x2="10" y2="0" gradientUnits="userSpaceOnUse">
        <stop stop-color="#111415"/>
        <stop offset=".45" stop-color="#3d4142"/>
        <stop offset="1" stop-color="#171a1b"/>
      </linearGradient>
    </defs>
    <path class="tonearm-shadow" d="M359 -600 L192 78 Q188 96 174 108 L153 127" stroke="#000" stroke-width="9"/>
    <path d="M356 -600 L189 78 Q185 96 171 108 L150 127" stroke="#222626" stroke-width="8"/>
    <path d="M356 -600 L189 78 Q185 96 171 108 L150 127" stroke="url(#tonearm-metal)" stroke-width="5.5"/>
    <path d="M354 -600 L187 78 Q183 95 170 106 L149 125" stroke="#f0eee5" stroke-opacity=".65" stroke-width="1.1"/>
    <g transform="translate(148 129) rotate(44)">
      <rect x="-4" y="-10" width="8" height="14" rx="1" fill="#878b88"/>
      <path d="M-4 -7 H4 M-4 -4 H4 M-4 -1 H4" stroke="#303535" stroke-width="1.5"/>
      <path d="M-9 1 H9 L10 30 H-10 Z" fill="url(#headshell-metal)" stroke="#45494a" stroke-width=".7"/>
      <path d="M-6 5 H6 M-6 9 H6" stroke="#777a77" stroke-width="1"/>
      <rect x="-5" y="13" width="3" height="5" rx="1" fill="#0a0c0d"/>
      <rect x="2" y="13" width="3" height="5" rx="1" fill="#0a0c0d"/>
      <circle cx="-5" cy="23" r="1.5" fill="#a7aaa5"/>
      <circle cx="5" cy="23" r="1.5" fill="#a7aaa5"/>
      <path d="M-4 31 V35 H4 V31" fill="#090b0c"/>
      <path d="M0 35 V38" stroke="#bbbcb4" stroke-width="1"/>
    </g>
  </svg>
  <div class="vinyl-time"><span data-current-time-hero>0:00</span><span>/</span><span data-hero-duration>4:08</span></div>
</div>
<?php }

function FeaturedTrackList($tracks) { ?>
<div class="track-list" data-track-list><?php foreach (array_slice($tracks, 0, 4) as $i => $track) TrackRow($track, $i); ?></div>
<?php }

function TrackRow($track, $index) { ?>
<button class="track-row" type="button" data-play-track="<?= htmlspecialchars($track['id']) ?>" aria-label="Play <?= htmlspecialchars($track['title'].' by '.$track['artist']) ?>">
  <span class="track-index"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span><img src="<?= htmlspecialchars($track['coverUrl']) ?>" alt="" loading="lazy"><span class="track-meta"><strong><?= htmlspecialchars($track['title']) ?></strong><small><?= htmlspecialchars($track['artist']) ?></small></span><span class="track-duration" data-duration="<?= htmlspecialchars($track['id']) ?>"><?= htmlspecialchars($track['duration']) ?></span><span class="row-play" aria-hidden="true">▶</span>
</button>
<?php }

function MusicDiscovery($tracks) { ?>
<section class="discovery" id="discover">
  <?php MusicCardWall($tracks); ?>
  <div class="discovery-shade" aria-hidden="true"></div>
  <div class="discovery-content"><p class="eyebrow light-eyebrow">YOUR NEXT OBSESSION IS IN HERE</p><h2>Good things<br>are <em>around.</em></h2>
    <?php MusicSearchBar(); ?>
    <p class="search-feedback" aria-live="polite">A few things we can't stop listening to</p>
    <?php AlbumCarousel($tracks); ?>
  </div>
  <div class="discovery-caption"><span>THE LISTENING ROOM</span><span>CURATED WITH FEELING&nbsp; ✳</span></div>
</section>
<?php }

function MusicCardWall($tracks) {
  if (!$tracks) return;
  $colors = ['#233b43', '#483b60', '#a35235', '#314945', '#66552b', '#343947']; ?>
<div class="collage-bg" aria-hidden="true">
  <div class="music-card-wall">
    <?php for ($column = 0; $column < 10; $column++) { ?>
      <div class="music-wall-column">
        <?php for ($row = 0; $row < 7; $row++) {
          $index = ($column * 7 + $row * 5 + intdiv($row, 3)) % count($tracks);
          $track = $tracks[$index];
          $color = $colors[($column + $row * 2) % count($colors)]; ?>
          <div class="music-wall-card" style="--wall-card-color: <?= $color ?>">
            <div class="wall-card-art">
              <img src="<?= htmlspecialchars($track['coverUrl']) ?>" alt="" loading="lazy" decoding="async">
              <span><?= htmlspecialchars($track['album']) ?></span>
            </div>
            <strong><?= htmlspecialchars($track['title']) ?></strong>
            <small><?= htmlspecialchars($track['artist']) ?></small>
            <div class="wall-card-progress"><i style="width: <?= 20 + (($column * 13 + $row * 17) % 60) ?>%"></i></div>
            <div class="wall-card-time"><span>1:24</span><span><?= htmlspecialchars($track['duration']) ?></span></div>
            <div class="wall-card-controls"><span>⌁</span><span>◀</span><b>Ⅱ</b><span>▶</span><span>↻</span></div>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
</div>
<?php }

function AlbumCard($track, $i) { ?>
<button class="album-card card-<?= $i+1 ?>" type="button" data-play-track="<?= htmlspecialchars($track['id']) ?>" data-search="<?= htmlspecialchars(strtolower($track['title'].' '.$track['artist'].' '.$track['album'])) ?>" aria-label="Play <?= htmlspecialchars($track['title'].' by '.$track['artist']) ?>">
  <span class="card-image"><img src="<?= htmlspecialchars($track['coverUrl']) ?>" alt="<?= htmlspecialchars($track['album']) ?> album artwork" loading="lazy"><span class="card-play" aria-hidden="true">▶</span></span><span class="card-details"><strong><?= htmlspecialchars($track['title']) ?></strong><small><?= htmlspecialchars($track['artist']) ?></small></span>
</button>
<?php }

function MusicSearchBar() { ?>
<form class="search-form" role="search"><label class="sr-only" for="music-search">Search music</label><span class="search-icon" aria-hidden="true">⌕</span><input id="music-search" type="search" placeholder="Search a song, artist or album..." autocomplete="off"><button type="button" class="clear-search" aria-label="Clear search" hidden>×</button><span class="search-key" aria-hidden="true">↵</span></form>
<?php }

function AlbumCarousel($tracks) { ?>
<div class="coverflow">
  <div class="album-carousel" id="album-carousel" role="region" aria-roledescription="carousel" aria-label="Music picks. Swipe or use arrow keys to browse." tabindex="0">
    <?php foreach ($tracks as $i => $track) { ?><div class="album-slide"><?php AlbumCard($track, $i); ?></div><?php } ?>
  </div>
  <div class="coverflow-dock" aria-label="Music picks controls">
    <button type="button" class="coverflow-button" data-carousel-prev aria-label="Previous card" aria-controls="album-carousel">‹</button>
    <button type="button" class="coverflow-button coverflow-play" data-carousel-play aria-label="Play selected track"><span data-carousel-play-icon>▶</span></button>
    <button type="button" class="coverflow-button" data-carousel-next aria-label="Next card" aria-controls="album-carousel">›</button>
    <div class="coverflow-selected"><img data-carousel-art src="<?= htmlspecialchars($tracks[0]['coverUrl'] ?? '') ?>" alt=""><span><strong data-carousel-title>Select a track</strong><small data-carousel-artist>Swipe to explore</small></span></div>
    <span class="coverflow-count" data-carousel-count aria-live="polite" aria-atomic="true"></span>
  </div>
  <div class="coverflow-scroll"><span>SWIPE TO EXPLORE</span><input type="range" data-carousel-position min="0" max="<?= max(0, count($tracks) - 1) ?>" value="0" step="1" aria-label="Browse music cards" aria-controls="album-carousel"></div>
</div>
<?php }

function FeaturedAudioPlayer() { ?>
<section class="player-section" id="player">
 <div class="player-heading"><div><p class="eyebrow">TAKE A MOMENT</p><h2>Stay for<br><em>one more.</em></h2></div><span class="player-note">A good record deserves<br>your full attention.</span></div>
 <div class="audio-player">
   <div class="player-art"><img data-player-art src="" alt="Now playing album artwork"><span class="art-sticker">STEREO<br>SESSION <b>✳</b></span></div>
   <div class="player-main"><div class="player-status"><span><i class="live-dot"></i> NOW SPINNING</span><span data-playing-status>READY WHEN YOU ARE</span></div>
     <div class="player-track-info"><p class="player-album" data-player-album>STEREO SELECTS</p><h3 data-player-title>Pick a track</h3><p data-player-artist>Choose something from the list above</p></div>
      <?php PlayerControls(); ?><p class="player-message" data-player-message role="status">Select a song to start listening.</p>
   </div>
 </div>
 <div class="player-bottom"><span>MADE FOR THE LONG WAY HOME</span><span>PRESS PLAY. LET GO.</span></div>
</section>
<?php }

function PlayerControls() { ?>
<div class="player-controls"><?php ProgressBar(); ?><div class="transport"><button class="icon-button" type="button" data-prev aria-label="Previous track">|◀</button><button class="play-button" type="button" data-toggle-play aria-label="Play" disabled><span data-play-icon>▶</span></button><button class="icon-button" type="button" data-next aria-label="Next track">▶|</button><?php VolumeControl(); ?></div></div>
<?php }

function ProgressBar() { ?>
<div class="timeline"><span data-current-time>0:00</span><label class="sr-only" for="progress-slider">Track progress</label><input id="progress-slider" type="range" min="0" max="100" value="0" step="0.1" disabled><span data-total-time>0:00</span></div>
<?php }

function VolumeControl() { ?>
<div class="volume-control"><button class="icon-button" type="button" data-mute aria-label="Mute">◖</button><label class="sr-only" for="volume-slider">Volume</label><input id="volume-slider" type="range" min="0" max="1" value="0.8" step="0.01" aria-label="Volume"></div>
<?php }

function ArtistShowcase($artists) { ?>
<section class="artist-showcase" id="artists" style="--artist-accent: <?= htmlspecialchars($artists[0]['color']) ?>">
  <div class="artist-backdrop"><img data-artist-hero src="<?= htmlspecialchars($artists[0]['image']) ?>" alt="Portrait of <?= htmlspecialchars($artists[0]['name']) ?>" loading="lazy"></div><div class="artist-overlay"></div>
  <div class="artist-topline"><span>THE PEOPLE BEHIND THE MUSIC</span><span>ARTIST SPOTLIGHT&nbsp; / &nbsp;01—04</span></div>
  <div class="artist-feature"><p class="eyebrow light-eyebrow">IN GOOD COMPANY</p><h2 data-artist-name><?= htmlspecialchars($artists[0]['name']) ?></h2><p data-artist-genre><?= htmlspecialchars($artists[0]['genre']) ?></p><a href="#discover" class="artist-link" data-artist-link>LISTEN TO THE SELECTION <span>↗</span></a></div>
  <div class="artist-gallery" aria-label="Featured artists">
     <?php foreach ($artists as $i => $artist) ArtistCard($artist, $i); ?>
  </div>
  <div class="artist-footer"><span>STEREO SELECTS&nbsp; ©&nbsp; 2025</span><span>THAT'S A WRAP&nbsp; ↑</span></div>
</section>
<?php }

function ArtistCard($artist, $i) { ?>
<button class="artist-card <?= $i === 0 ? 'is-selected' : '' ?>" type="button" data-artist-index="<?= $i ?>" data-name="<?= htmlspecialchars($artist['name']) ?>" data-genre="<?= htmlspecialchars($artist['genre']) ?>" data-image="<?= htmlspecialchars($artist['image']) ?>" data-color="<?= htmlspecialchars($artist['color']) ?>" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"><img src="<?= htmlspecialchars($artist['image']) ?>" alt="Portrait of <?= htmlspecialchars($artist['name']) ?>" loading="lazy"><span><strong><?= htmlspecialchars($artist['name']) ?></strong><small><?= htmlspecialchars($artist['genre']) ?></small></span><i>↗</i></button>
<?php }
