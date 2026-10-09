const club = {
  name: 'Dolphin Netball Club',
  founded: 2025,
  motto: 'Train Hard, Play Fast',
  homeGround: 'Dolphin Netball Club',
  trainingDays: ['Saturday']
};

const events = [
  {
    name: 'Home Game',
    type: 'home',
    date: '2026-10-17T15:00:00',
    venue: 'Dolphin Courts',
    note: 'Opening match of the weekend series.'
  },
  {
    name: 'Training Session',
    type: 'training',
    date: '2026-10-20T18:00:00',
    venue: 'Club Hall',
    note: 'Conditioning and ball-handling drills.'
  },
  {
    name: 'Club Social',
    type: 'social',
    date: '2026-10-24T17:30:00',
    venue: 'Club House',
    note: 'Team bonding and new player welcome.'
  },
  {
    name: 'Away Fixture',
    type: 'away',
    date: '2026-10-31T13:00:00',
    venue: 'Harare Arena',
    note: 'Travel match against city rivals.'
  }
];

function renderEvents() {
  const list = document.getElementById('eventList');
  if (!list) return;

  let html = '';

  for (let i = 0; i < events.length; i += 1) {
    const ev = events[i];
    const when = formatDate(ev.date);
    html += `
      <div class="card event-card" data-index="${i}">
        <span class="tag">${ev.type.toUpperCase()}</span>
        <h3>${ev.name}</h3>
        <p>${when} &middot; ${ev.venue}</p>
        <p class="event-note" style="display:none; color:var(--muted); font-size:0.88rem;">${ev.note}</p>
      </div>
    `;
  }

  list.innerHTML = html;

  const cards = list.querySelectorAll('.event-card');
  cards.forEach(function (card) {
    const note = card.querySelector('.event-note');
    if (!note) return;

    card.addEventListener('mouseover', function () {
      note.style.display = 'block';
    });

    card.addEventListener('mouseout', function () {
      note.style.display = 'none';
    });
  });
}

function renderFixtureTable() {
  const body = document.getElementById('fixtureTableBody');
  if (!body) return;

  let rows = '';
  for (let i = 0; i < events.length; i += 1) {
    const ev = events[i];
    rows += `
      <tr>
        <td>${formatDate(ev.date)}</td>
        <td>${ev.name}</td>
        <td>${ev.venue}</td>
        <td>${ev.type}</td>
      </tr>
    `;
  }
  body.innerHTML = rows;
}

function formatDate(isoString) {
  if (!isoString) return 'TBC';

  const d = new Date(isoString);
  if (Number.isNaN(d.getTime())) return 'TBC';

  const options = {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };

  return d.toLocaleString('en-GB', options);
}

function getNextEvent() {
  const now = new Date();

  for (let i = 0; i < events.length; i += 1) {
    const eventDate = new Date(events[i].date);
    if (eventDate > now) {
      return events[i];
    }
  }

  return null;
}

function setupNavToggle() {
  const toggle = document.getElementById('navToggle');
  const links = document.getElementById('navLinks');
  if (!toggle || !links) return;

  toggle.addEventListener('click', function () {
    const isOpen = links.classList.toggle('open');
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });
}

function setupGalleryLightbox() {
  const grid = document.getElementById('galleryGrid');
  const lightbox = document.getElementById('lightbox');
  if (!grid || !lightbox) return;

  const closeBtn = document.getElementById('lightboxClose');
  const imageEl = document.getElementById('lightboxImage');
  const titleEl = document.getElementById('lightboxTitle');
  const captionEl = document.getElementById('lightboxCaption');

  if (!closeBtn || !imageEl || !titleEl || !captionEl) return;

  const items = grid.querySelectorAll('.gallery-item');
  items.forEach(function (item) {
    item.addEventListener('click', function () {
      const thumbnail = item.querySelector('.gallery-img');
      const caption = item.querySelector('.caption');
      if (!thumbnail || !imageEl) return;

      imageEl.src = thumbnail.src;
      imageEl.alt = thumbnail.alt;
      titleEl.textContent = item.dataset.title || thumbnail.alt;
      captionEl.textContent = item.dataset.caption || (caption ? caption.textContent : '');
      lightbox.classList.add('open');
    });
  });

  function closeLightbox() {
    lightbox.classList.remove('open');
  }

  closeBtn.addEventListener('click', closeLightbox);
  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) closeLightbox();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeLightbox();
  });
}

function togglePositionField() {
  const reasonSelect = document.getElementById('reason');
  const positionField = document.getElementById('field-position');
  const positionInput = document.getElementById('position');
  if (!reasonSelect || !positionField || !positionInput) return;

  const shouldShow = reasonSelect.value === 'join';
  positionField.style.display = shouldShow ? 'grid' : 'none';
  if (!shouldShow) {
    positionInput.value = '';
  }
}

function setupFormValidation() {
  const form = document.getElementById('clubForm');
  if (!form) return;

  const reasonSelect = document.getElementById('reason');
  if (reasonSelect) {
    reasonSelect.addEventListener('change', togglePositionField);
    togglePositionField();
  }

  function showError(fieldWrapper) {
    if (fieldWrapper) fieldWrapper.classList.add('invalid');
  }

  function clearError(fieldWrapper) {
    if (fieldWrapper) fieldWrapper.classList.remove('invalid');
  }

  function validateField(id) {
    const wrapper = document.getElementById('field-' + id);
    const input = document.getElementById(id);
    if (!wrapper || !input) return true;

    const value = input.value.trim();
    let valid = true;

    if (id === 'full_name') {
      valid = value.length >= 2;
    } else if (id === 'email') {
      const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      valid = pattern.test(value);
    } else if (id === 'phone') {
      valid = value === '' || /^[0-9+ ()-]{7,20}$/.test(value);
    } else if (id === 'position') {
      valid = reasonSelect ? reasonSelect.value !== 'join' || value.length > 0 : true;
    }

    if (valid) {
      clearError(wrapper);
    } else {
      showError(wrapper);
    }
    return valid;
  }

  form.addEventListener('submit', function (event) {
    const ids = ['full_name', 'email', 'phone', 'position'];
    let isValid = true;

    ids.forEach(function (id) {
      if (!validateField(id)) {
        isValid = false;
      }
    });

    if (!isValid) {
      event.preventDefault();
    }
  });

  ['full_name', 'email', 'phone', 'position'].forEach(function (id) {
    const input = document.getElementById(id);
    if (!input) return;

    input.addEventListener('blur', function () {
      validateField(id);
    });
  });
}

function startCountdown(id) {
  const el = document.getElementById(id);
  if (!el) return;

  const target = el.dataset.date ? new Date(el.dataset.date) : new Date(Date.now() + 1000 * 60 * 60 * 24 * 7);
  if (Number.isNaN(target.getTime())) return;

  const updateCountdown = function () {
    const now = new Date();
    const diff = Math.max(target - now, 0);

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((diff / (1000 * 60)) % 60);
    const seconds = Math.floor((diff / 1000) % 60);

    el.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
  };

  updateCountdown();
  setInterval(updateCountdown, 1000);
}

function setFooterYear() {
  const yearEl = document.getElementById('year');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }
}

window.onload = function () {
  renderEvents();
  renderFixtureTable();
  startCountdown('homeCountdown');
  startCountdown('activitiesCountdown');
  setupNavToggle();
  setupGalleryLightbox();
  setupFormValidation();
  setFooterYear();
};
