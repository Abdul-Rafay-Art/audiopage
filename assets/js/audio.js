(function () {
  'use strict';
  const tracks = window.STEREO_TRACKS || [];
  const audio = document.getElementById('global-audio');
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
  const state = { index: -1, busy: false };
  const playButton = $('[data-toggle-play]');
  const playIcon = $('[data-play-icon]');
  const slider = $('#progress-slider');
  const volume = $('#volume-slider');
  const message = $('[data-player-message]');
  const live = $('#live-status');
  let seekOnChange = false;
  const coverflow = createCoverflow();

  function formatTime(value) {
    if (!Number.isFinite(value) || value < 0) return '0:00';
    return `${Math.floor(value / 60)}:${String(Math.floor(value % 60)).padStart(2, '0')}`;
  }
  function say(text) { message.textContent = text; live.textContent = text; }
  function current() { return tracks[state.index]; }
  function sync() {
    const track = current();
    const playing = !audio.paused && !audio.ended;
    playIcon.textContent = playing ? 'Ⅱ' : '▶';
    playButton.setAttribute('aria-label', playing ? 'Pause' : 'Play');
    $$('.track-row, .album-card').forEach((item) => {
      const active = !!track && item.dataset.playTrack === track.id;
      item.classList.toggle('is-active', active);
      if (item.matches('.track-row')) item.setAttribute('aria-current', active ? 'true' : 'false');
    });
    document.body.classList.toggle('is-playing', playing);
    $('.vinyl-disc')?.classList.toggle('spinning', playing);
    coverflow.sync();
  }
  function selectTrack(index, autoplay = true) {
    if (!tracks.length) return;
    state.index = (index + tracks.length) % tracks.length;
    const track = current();
    coverflow.select(track.id);
    audio.pause();
    audio.src = track.audioUrl;
    audio.load();
    $('[data-player-title]').textContent = track.title;
    $('[data-player-artist]').textContent = track.artist;
    $('[data-player-album]').textContent = track.album.toUpperCase();
    $('[data-hero-duration]').textContent = track.duration;
    const art = $('[data-player-art]');
    art.src = track.coverUrl;
    art.alt = `${track.album} artwork — ${track.title} by ${track.artist}`;
    $('[data-playing-status]').textContent = 'READY TO PLAY';
    $('[data-current-time]').textContent = '0:00';
    $('[data-current-time-hero]').textContent = '0:00';
    $('[data-total-time]').textContent = track.duration;
    slider.value = 0;
    slider.disabled = false;
    playButton.disabled = false;
    audio.volume = Number(volume.value);
    say('Loading track…');
    sync();
    if (autoplay) startPlayback();
  }
  async function startPlayback() {
    if (!current()) { selectTrack(0, false); }
    if (!audio.src) return;
    state.busy = true;
    document.body.classList.add('is-loading');
    $('[data-playing-status]').textContent = 'LOADING';
    say('Loading audio…');
    try { await audio.play(); }
    catch (error) {
      if (error.name !== 'AbortError') {
        $('[data-playing-status]').textContent = 'UNAVAILABLE';
        document.body.classList.add('has-error');
        say('This track could not be played. Try another selection.');
      }
    } finally { state.busy = false; document.body.classList.remove('is-loading'); }
  }
  function playOrPause() { if (audio.paused) startPlayback(); else audio.pause(); }
  $$('[data-play-track]').forEach((button) => button.addEventListener('click', () => {
    if (button.matches('.album-card') && !coverflow.activate(button)) return;
    const index = tracks.findIndex((track) => track.id === button.dataset.playTrack);
    if (index < 0) return;
    if (state.index === index && audio.src) playOrPause(); else selectTrack(index, true);
  }));
  playButton.addEventListener('click', playOrPause);
  $('[data-prev]').addEventListener('click', () => selectTrack((state.index < 0 ? 0 : state.index - 1), true));
  $('[data-next]').addEventListener('click', () => selectTrack((state.index + 1) % tracks.length, true));
  audio.addEventListener('play', () => { document.body.classList.remove('has-error'); $('[data-playing-status]').textContent = 'NOW PLAYING'; say(`Now playing ${current()?.title || ''} by ${current()?.artist || ''}.`); sync(); });
  audio.addEventListener('pause', () => { if (!audio.ended) $('[data-playing-status]').textContent = 'PAUSED'; sync(); });
  audio.addEventListener('loadedmetadata', () => { $('[data-total-time]').textContent = formatTime(audio.duration); slider.max = audio.duration || 100; say('Audio ready.'); });
  audio.addEventListener('timeupdate', () => {
    if (!seekOnChange) slider.value = audio.currentTime || 0;
    $('[data-current-time]').textContent = formatTime(audio.currentTime);
    $('[data-current-time-hero]').textContent = formatTime(audio.currentTime);
  });
  audio.addEventListener('waiting', () => { $('[data-playing-status]').textContent = 'BUFFERING'; say('Buffering audio…'); });
  audio.addEventListener('canplay', () => { if (!audio.paused) $('[data-playing-status]').textContent = 'NOW PLAYING'; });
  audio.addEventListener('error', () => {
    if (!current()) return;
    $('[data-playing-status]').textContent = 'UNAVAILABLE';
    document.body.classList.add('has-error');
    say('Audio unavailable. Check your connection or choose another track.');
    sync();
  });
  audio.addEventListener('ended', () => selectTrack((state.index + 1) % tracks.length, true));
  slider.addEventListener('input', () => { seekOnChange = true; $('[data-current-time]').textContent = formatTime(Number(slider.value)); });
  slider.addEventListener('change', () => { if (Number.isFinite(audio.duration)) audio.currentTime = Math.min(audio.duration, Math.max(0, Number(slider.value))); seekOnChange = false; });
  volume.addEventListener('input', () => { audio.volume = Number(volume.value); audio.muted = audio.volume === 0; $('[data-mute]').setAttribute('aria-label', audio.muted ? 'Unmute' : 'Mute'); });
  $('[data-mute]').addEventListener('click', (event) => { audio.muted = !audio.muted; event.currentTarget.setAttribute('aria-label', audio.muted ? 'Unmute' : 'Mute'); event.currentTarget.textContent = audio.muted ? '◌' : '◖'; });

  function createCoverflow() {
    const rail = $('.album-carousel');
    const cards = $$('.album-card', rail);
    const previous = $('[data-carousel-prev]');
    const next = $('[data-carousel-next]');
    const toggle = $('[data-carousel-play]');
    const position = $('[data-carousel-position]');
    const artwork = $('[data-carousel-art]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let visible = cards.slice();
    let selected = Math.min(2, visible.length - 1);
    let selectedId = visible[selected]?.dataset.playTrack;
    let frame = 0;
    let drag = null;
    let suppressClickUntil = 0;
    let wheelDelta = 0;
    let wheelTime = 0;

    function targetScroll(index) {
      const slide = visible[index]?.parentElement;
      return slide ? slide.offsetLeft + slide.offsetWidth / 2 - rail.clientWidth / 2 : 0;
    }
    function go(index, behavior = 'smooth') {
      if (!visible.length) return;
      index = Math.max(0, Math.min(visible.length - 1, index));
      rail.scrollTo({ left: targetScroll(index), behavior: reducedMotion.matches ? 'instant' : behavior });
      if (behavior === 'instant' || reducedMotion.matches) render();
    }
    function syncSelected() {
      const track = tracks.find(item => item.id === selectedId);
      const playing = !!track && current()?.id === track.id && !audio.paused && !audio.ended;
      $('[data-carousel-play-icon]').textContent = playing ? 'Ⅱ' : '▶';
      toggle.setAttribute('aria-label', track ? `${playing ? 'Pause' : 'Play'} ${track.title}` : 'Play selected track');
      toggle.disabled = !track;
      if (visible[selected]) visible[selected].setAttribute('aria-label', `${playing ? 'Pause' : 'Play'} ${track.title} by ${track.artist}`);
    }
    function render() {
      frame = 0;
      const step = visible[0]?.parentElement.offsetWidth || 1;
      let nearest = 0;
      let nearestDistance = Infinity;
      visible.forEach((card, index) => {
        const distance = (targetScroll(index) - rail.scrollLeft) / step;
        const absolute = Math.abs(distance);
        if (absolute < nearestDistance) { nearest = index; nearestDistance = absolute; }
        const depth = Math.min(absolute, 3);
        card.style.transform = `perspective(850px) translateY(${depth * 12}px) rotateY(${Math.max(-45, Math.min(45, distance * -32))}deg) scale(${1 - depth * .13})`;
        card.style.opacity = absolute > 2.8 ? '0' : '1';
        card.style.filter = `brightness(${1 - depth * .12})`;
        card.style.pointerEvents = absolute > 2.8 ? 'none' : '';
        card.parentElement.style.zIndex = String(10 - Math.round(absolute * 2));
      });
      selected = visible.length ? nearest : -1;
      selectedId = visible[selected]?.dataset.playTrack;
      cards.forEach(card => {
        const centered = card === visible[selected];
        card.classList.toggle('is-centered', centered);
        card.tabIndex = centered ? 0 : -1;
        card.setAttribute('aria-current', centered ? 'true' : 'false');
        if (!centered) card.setAttribute('aria-label', `Select ${$('.card-details strong', card).textContent}`);
      });
      const track = tracks.find(item => item.id === selectedId);
      artwork.hidden = !track;
      if (track && artwork.getAttribute('src') !== track.coverUrl) artwork.src = track.coverUrl;
      $('[data-carousel-title]').textContent = track?.title || 'No matching tracks';
      $('[data-carousel-artist]').textContent = track?.artist || 'Try a different search';
      $('[data-carousel-count]').textContent = `${selected + 1} / ${visible.length}`;
      previous.disabled = selected <= 0;
      next.disabled = selected < 0 || selected >= visible.length - 1;
      position.max = Math.max(0, visible.length - 1);
      position.value = Math.max(0, selected);
      position.disabled = visible.length < 2;
      position.setAttribute('aria-valuetext', track ? `${selected + 1} of ${visible.length}: ${track.title}` : 'No matching tracks');
      syncSelected();
    }
    function scheduleRender() { if (!frame) frame = requestAnimationFrame(render); }
    rail.addEventListener('scroll', scheduleRender, { passive: true });
    previous.addEventListener('click', () => go(selected - 1));
    next.addEventListener('click', () => go(selected + 1));
    position.addEventListener('input', () => go(Number(position.value), 'instant'));
    toggle.addEventListener('click', () => {
      const index = tracks.findIndex(track => track.id === selectedId);
      if (index < 0) return;
      if (state.index === index && audio.src) playOrPause(); else selectTrack(index, true);
    });
    rail.addEventListener('keydown', event => {
      const targets = { ArrowLeft: selected - 1, ArrowRight: selected + 1, Home: 0, End: visible.length - 1 };
      if (!(event.key in targets)) return;
      event.preventDefault();
      rail.focus({ preventScroll: true });
      go(targets[event.key]);
    });
    rail.addEventListener('wheel', event => {
      if (event.ctrlKey || Math.abs(event.deltaX) > Math.abs(event.deltaY) || visible.length < 2) return;
      const direction = Math.sign(event.deltaY);
      if (!direction || (direction < 0 && selected === 0) || (direction > 0 && selected === visible.length - 1)) return;
      event.preventDefault();
      const now = performance.now();
      if (now - wheelTime < 280) return;
      wheelDelta += event.deltaY * (event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? rail.clientWidth : 1);
      if (Math.abs(wheelDelta) < 35) return;
      go(selected + Math.sign(wheelDelta));
      wheelDelta = 0;
      wheelTime = now;
    }, { passive: false });
    rail.addEventListener('pointerdown', event => {
      if (event.pointerType !== 'mouse' || event.button !== 0) return;
      drag = { id: event.pointerId, x: event.clientX, scroll: rail.scrollLeft, moved: false };
    });
    rail.addEventListener('pointermove', event => {
      if (!drag || event.pointerId !== drag.id) return;
      const delta = event.clientX - drag.x;
      if (!drag.moved && Math.abs(delta) < 6) return;
      if (!drag.moved) { drag.moved = true; rail.classList.add('is-dragging'); rail.setPointerCapture(event.pointerId); }
      rail.scrollLeft = drag.scroll - delta;
      event.preventDefault();
    });
    function finishDrag(event) {
      if (!drag || event.pointerId !== drag.id) return;
      const moved = drag.moved;
      drag = null;
      if (!moved) return;
      suppressClickUntil = performance.now() + 250;
      render();
      rail.classList.remove('is-dragging');
      if (rail.hasPointerCapture(event.pointerId)) rail.releasePointerCapture(event.pointerId);
      go(selected);
    }
    rail.addEventListener('pointerup', finishDrag);
    rail.addEventListener('pointerleave', () => { if (drag && !drag.moved) drag = null; });
    rail.addEventListener('pointercancel', finishDrag);
    rail.addEventListener('lostpointercapture', finishDrag);
    rail.addEventListener('dragstart', event => event.preventDefault());
    new ResizeObserver(() => go(Math.max(0, visible.findIndex(card => card.dataset.playTrack === selectedId)), 'instant')).observe(rail);
    go(selected, 'instant');
    render();

    return {
      sync: syncSelected,
      select(id) {
        const index = visible.findIndex(card => card.dataset.playTrack === id);
        if (index >= 0) go(index);
      },
      activate(card) {
        if (performance.now() < suppressClickUntil) return false;
        const index = visible.indexOf(card);
        if (index !== selected) { go(index); return false; }
        return true;
      },
      filter() {
        cards.forEach(card => { card.parentElement.hidden = card.hidden; });
        visible = cards.filter(card => !card.hidden);
        const index = visible.findIndex(card => card.dataset.playTrack === selectedId);
        go(Math.max(0, index), 'instant');
        render();
      },
    };
  }

  const search = $('#music-search');
  const clear = $('.clear-search');
  search.addEventListener('input', () => {
    const query = search.value.trim().toLowerCase();
    let count = 0;
    $$('.album-card').forEach((card) => { const match = !query || card.dataset.search.includes(query); card.hidden = !match; if (match) count++; });
    coverflow.filter();
    clear.hidden = !query;
    $('.search-feedback').textContent = query ? (count ? `${count} ${count === 1 ? 'track' : 'tracks'} found` : 'No tracks found — try another search') : 'A few things we can\'t stop listening to';
  });
  clear.addEventListener('click', () => { search.value = ''; search.dispatchEvent(new Event('input')); search.focus(); });
  $('.search-form').addEventListener('submit', (event) => event.preventDefault());

  $$('.artist-card').forEach((card) => card.addEventListener('click', () => {
    $$('.artist-card').forEach((item) => { item.classList.remove('is-selected'); item.setAttribute('aria-pressed', 'false'); });
    card.classList.add('is-selected'); card.setAttribute('aria-pressed', 'true');
    $('[data-artist-name]').textContent = card.dataset.name;
    $('[data-artist-genre]').textContent = card.dataset.genre;
    document.querySelector('.artist-showcase').style.setProperty('--artist-accent', card.dataset.color);
    const hero = $('[data-artist-hero]'); hero.style.opacity = '0.2';
    window.setTimeout(() => { hero.src = card.dataset.image; hero.alt = `Portrait of ${card.dataset.name}`; hero.style.opacity = ''; }, 120);
  }));
  const menu = $('.menu-toggle');
  menu?.addEventListener('click', () => { const open = menu.getAttribute('aria-expanded') !== 'true'; menu.setAttribute('aria-expanded', String(open)); $('.main-nav').classList.toggle('is-open', open); });
  $$('.main-nav a').forEach((link) => link.addEventListener('click', () => { menu.setAttribute('aria-expanded', 'false'); $('.main-nav').classList.remove('is-open'); }));
})();
