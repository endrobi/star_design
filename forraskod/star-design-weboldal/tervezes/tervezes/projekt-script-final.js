/* =============================================================
   projekt-script-final.js – Projekt Tervező interaktív logikája
   =============================================================*/

/* =============================================================
   GLOBÁLIS ÁLLAPOTVÁLTOZÓK
   ============================================================= */

/* Az aktuálisan megjelenített lépés száma (1–7) */
let currentStep = 1;

/* Az összes lépés száma */
const totalSteps = 7;
let maxReachedStep = 1;

/* Az összegyűjtött adatok objektuma – minden lépés ebbe ment */
const formData = {
  categories: [],
  colors: [],
  styles: [],
  hasText: null,
  textContent: '',
  favoriteFonts: [],
  description: '',
  images: [],
  imageFiles: [],
  links: '',
  name: '',
  email: '',
  phone: '',
  notes: ''
};

// Színválasztó állapot
let currentColor = {
  hue: 0,
  saturation: 100,
  lightness: 50
};

// ============================================
// VÉGLEGES IKONOK
// ============================================

const CATEGORIES = [
  { id: 'logo', name: 'Logó', icon: '<img src="ikonok/logo.png">', description: 'Vállalkozás/márka azonosító' },
  { id: 'polo', name: 'Pólóminta', icon: '<img src="ikonok/polo.png">', description: 'Egyedi pólóra való design' },
  { id: 'kituzo', name: 'Kitűző', icon: '<img src="ikonok/kituzo.png">', description: 'Pin/jelvény tervezés' },
  { id: 'plakat', name: 'Plakát/Meghívó', icon: '<img src="ikonok/plakat-meghivo.png">', description: 'Esemény/reklám anyag' },
  { id: 'bogre', name: 'Bögre minta', icon: '<img src="ikonok/bogrek.png">', description: 'Bögre design' },
  { id: 'egyeb', name: 'Egyéb', icon: '<img src="ikonok/egyeb.png">', description: 'Más típusú design' }
];

// ============================================
// STÍLUSOK (képekkel - placeholder URL-ek)
// ============================================

const STYLE_OPTIONS = [
  { 
    id: 'minimal', 
    name: 'Minimál', 
    icon: '<img src="ikonok/minimal.png">', 
    description: 'Egyszerű, tiszta vonalak',
    example: 'Fekete-fehér, geometrikus formák, kevés szín, tiszta betűtípusok',
    imageUrl: 'kepek/1.png' 
  },
  { 
    id: 'playful', 
    name: 'Játékos', 
    icon: '<img src="ikonok/jatekos.png">', 
    description: 'Vicces, színes, dinamikus',
    example: 'Élénk színek, cartoon stílus, kerek formák, vidám hangulatú',
    imageUrl: 'kepek/2.png'
  },
  { 
    id: 'elegant', 
    name: 'Elegáns', 
    icon: '<img src="ikonok/elegans.png">', 
    description: 'Kifinomult, időtlen',
    example: 'Arany/ezüst akcentek, serif betűk, kiegyensúlyozott, prémium érzés',
    imageUrl: 'kepek/3.png'
  },
  { 
    id: 'nature', 
    name: 'Természetes', 
    icon: '<img src="ikonok/termeszetes.png">', 
    description: 'Organikus, földszínek',
    example: 'Zöld/barna tónusok, levél/fa motívumok, organic formák',
    imageUrl: 'kepek/4.png'
  },
  { 
    id: 'professional', 
    name: 'Professzionális', 
    icon: '<img src="ikonok/profi.png">', 
    description: 'Komoly, megbízható',
    example: 'Kék/szürke színek, tiszta elrendezés, sans-serif betűk, corporate',
    imageUrl: 'kepek/5.png'
  },
  { 
    id: 'vintage', 
    name: 'Vintage', 
    icon: '<img src="ikonok/vintage.png">', 
    description: 'Retró, nosztalgikus',
    example: 'Halványult színek, régi nyomdai stílus, textúrák, retro betűk',
    imageUrl: 'kepek/6.png'
  },
  { 
    id: 'modern', 
    name: 'Modern', 
    icon: '<img src="ikonok/modern.png">', 
    description: 'Trendy, kortárs',
    example: 'Merész színkombók, gradientek, modern betűk, innovatív',
    imageUrl: 'kepek/7.png'
  },
  { 
    id: 'bold', 
    name: 'Merész', 
    icon: '<img src="ikonok/meresz.png">', 
    description: 'Feltűnő, vibráló',
    example: 'Kontrasztos színek, nagy betűk, határozott, figyelemfelkeltő',
    imageUrl: 'kepek/8.png'
  }
];

// ============================================
// BETŰTÍPUSOK
// ============================================

const FONT_OPTIONS = [
  { name: 'Impact', family: 'Impact, sans-serif', weight: '900', desc: 'Vastag, erős, figyelemfelkeltő' },
  { name: 'Courier New', family: 'Courier New, monospace', weight: '400', desc: 'Írógép stílus, technikai' },
  { name: 'Times New Roman', family: 'Times New Roman, serif', weight: '400', desc: 'Klasszikus, elegáns serif' },
  { name: 'Comic Sans MS', family: 'Comic Sans MS, cursive', weight: '400', desc: 'Casual, barátságos' },
  { name: 'Georgia', family: 'Georgia, serif', weight: '700', desc: 'Elegáns, olvasható serif' },
  { name: 'Brush Script MT', family: 'Brush Script MT, cursive', weight: '400', desc: 'Kézírásos, művészi' },
  { name: 'Arial Black', family: 'Arial Black, sans-serif', weight: '900', desc: 'Extra vastag, modern' },
  { name: 'Trebuchet MS', family: 'Trebuchet MS, sans-serif', weight: '400', desc: 'Lekerekített, modern' }
];

/* =============================================================
   INICIALIZÁLÁS – DOM betöltése után fut le
   Megjeleníti az első lépést és a progress bar-t
   ============================================================= */
document.addEventListener('DOMContentLoaded', function () {
  renderStepIndicators();
  renderStep();
  updateProgress();
});

/* updateProgress: a progress bar szélességét frissíti az aktuális lépés alapján */
function updateProgress() {
  const progress = (currentStep / totalSteps) * 100;
  document.getElementById('progressBar').style.width = progress + '%';
}

/* renderStepIndicators: a számozott körök (1–7) újrarajzolása
   - completed (narancssárga): már elvégzett lépések
   - active (narancssárga, kiemelve): jelenlegi lépés
   - locked (halvány): még nem látogatott lépések */
function renderStepIndicators() {
  const container = document.getElementById('stepIndicators');
  container.innerHTML = '';
  
  for (let i = 1; i <= totalSteps; i++) {
    const btn = document.createElement('button');
    btn.className = 'step-indicator';
    btn.textContent = i;
    
    if (i < currentStep) btn.classList.add('completed');
    else if (i === currentStep) btn.classList.add('active');
    
   if (i > maxReachedStep) btn.classList.add('locked');
    btn.onclick = () => goToStep(i);
    container.appendChild(btn);
  }
}

/* goToStep: visszalépéshez – csak már látogatott lépésre lehet ugrani */
function goToStep(step) {
  if (step > maxReachedStep) return; // előre nem lehet ugrani
  currentStep = step;
  renderStep();
  renderStepIndicators();
}

/* nextStep: következő lépésre lép*/
function nextStep() {
  if (currentStep < totalSteps) {
    currentStep++;
    if (currentStep > maxReachedStep) maxReachedStep = currentStep;
    renderStep();
    updateProgress();          
    renderStepIndicators();
    window.scrollTo(0, 0);     
  }
}

/* prevStep: előző lépésre lép */
function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    renderStep();
    updateProgress();
    renderStepIndicators();
    window.scrollTo(0, 0);
  }
}

/* renderStep: az aktuális lépés tartalmát jeleníti meg a kártyában */
function renderStep() {
  const content = document.getElementById('formContent');

  /* Switch alapján meghívja a megfelelő renderStepX() függvényt */
  switch (currentStep) {
    case 1: renderStep1(content); break; /* Kategória választás */
    case 2: renderStep2(content); break; /* Szín választás */
    case 3: renderStep3(content); break; /* Stílus választás */
    case 4: renderStep4(content); break; /* Szöveg/betűtípus */
    case 5: renderStep5(content); break; /* Inspirációk */
    case 6: renderStep6(content); break; /* Kapcsolati adatok */
    case 7: renderStep7(content); break; /* Összegzés + küldés */
  }
}

// ============================================
// LÉPÉS 1: KATEGÓRIA
// ============================================

function renderStep1(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/tervezes.png"></div>
      <h2 class="step-title">Mit szeretnél terveztetni?</h2>
      <p class="step-subtitle">Válassz egyet vagy többet is!</p>
    </div>
    
    <div class="category-grid" id="categoryGrid"></div>
    
    <div class="btn-center" id="nextBtnContainer"></div>
  `;
  
  const grid = document.getElementById('categoryGrid');
  CATEGORIES.forEach(cat => {
    const card = document.createElement('div');
    card.className = 'category-card';
    if (formData.categories.includes(cat.id)) card.classList.add('selected');
    
    card.innerHTML = `
      <div class="category-icon">${cat.icon}</div>
      <div class="category-name">${cat.name}</div>
      <div class="category-description">${cat.description}</div>
    `;
    
    card.onclick = () => toggleCategory(cat.id);
    grid.appendChild(card);
  });
  
  updateNextButton1();
}

function toggleCategory(id) {
  const clickedCard = event.currentTarget;
  clickedCard.classList.add('icon-pop');
  setTimeout(() => clickedCard.classList.remove('icon-pop'), 600);

  if (formData.categories.includes(id)) {
    formData.categories = formData.categories.filter(c => c !== id);
  } else {
    formData.categories.push(id);
  }
  renderStep1(document.getElementById('formContent'));
}

function updateNextButton1() {
  const container = document.getElementById('nextBtnContainer');
  if (formData.categories.length > 0) {
    container.innerHTML = '<button class="btn-primary" onclick="nextStep()">Következő: Színek ▶</button>';
  } else {
    container.innerHTML = '';
  }
}

// ============================================
// LÉPÉS 2: JAVÍTOTT SZÍNVÁLASZTÓ
// ============================================

function renderStep2(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/szinek.png"></div>
      <h2 class="step-title">Milyen színekkel dolgozzunk?</h2>
      <p class="step-subtitle">Állítsd be a színt a sliderek segítségével, majd add hozzá! (${formData.colors.length}/5)</p>
    </div>
    
    <div class="color-picker-paint">
      <h3 class="picker-title">🎨 Színválasztó</h3>
      
      <div class="color-picker-sliders">
        <div class="slider-group">
          <div class="slider-label">
            <span>Árnyalat</span>
            <span class="slider-value" id="hueValue">0°</span>
          </div>
          <input type="range" min="0" max="360" value="0" class="color-slider hue-slider" id="hueSlider">
        </div>
        
        <div class="slider-group">
          <div class="slider-label">
            <span>Telítettség</span>
            <span class="slider-value" id="satValue">100%</span>
          </div>
          <input type="range" min="0" max="100" value="100" class="color-slider" id="satSlider">
        </div>
        
        <div class="slider-group">
          <div class="slider-label">
            <span>Világosság</span>
            <span class="slider-value" id="lightValue">50%</span>
          </div>
          <input type="range" min="0" max="100" value="50" class="color-slider" id="lightSlider">
        </div>
      </div>
      
      <div class="color-picker-preview-side">
        <div class="color-preview-current">
          <div class="preview-box" id="previewBox"></div>
          <div class="preview-info">
            <div class="preview-label">Aktuális szín</div>
            <div class="preview-hex" id="previewHex">#FF0000</div>
          </div>
          <button class="btn-add-color" onclick="addColorToSelection()" id="addColorBtn">+ Hozzáadás</button>
        </div>
      </div>
    </div>
    
    <div id="selectedColorsPreview"></div>
    
    <div class="button-row">
      <button class="btn-secondary" onclick="prevStep()">◀ Vissza</button>
      <button class="btn-primary" onclick="nextStep()" ${formData.colors.length < 2 ? 'disabled' : ''}>Következő: Stílus ▶</button>
    </div>
  `;
  
  initColorPicker();
  
  if (formData.colors.length > 0) {
    renderColorRectangle();
  }
}

function initColorPicker() {
  const hueSlider = document.getElementById('hueSlider');
  const satSlider = document.getElementById('satSlider');
  const lightSlider = document.getElementById('lightSlider');
  
  // Sliderek event listener
  hueSlider.oninput = () => {
    currentColor.hue = parseInt(hueSlider.value);
    document.getElementById('hueValue').textContent = currentColor.hue + '°';
    updateColorPreview();
    updateSliderBackgrounds();
  };
  
  satSlider.oninput = () => {
    currentColor.saturation = parseInt(satSlider.value);
    document.getElementById('satValue').textContent = currentColor.saturation + '%';
    updateColorPreview();
    updateSliderBackgrounds();
  };
  
  lightSlider.oninput = () => {
    currentColor.lightness = parseInt(lightSlider.value);
    document.getElementById('lightValue').textContent = currentColor.lightness + '%';
    updateColorPreview();
    updateSliderBackgrounds();
  };
  
  // Kezdeti állapot
  updateColorPreview();
  updateSliderBackgrounds();
}

function updateColorPreview() {
  const {hue, saturation, lightness} = currentColor;
  const hslColor = `hsl(${hue}, ${saturation}%, ${lightness}%)`;
  const hexColor = hslToHex(hue, saturation, lightness);
  
  document.getElementById('previewBox').style.background = hslColor;
  document.getElementById('previewHex').textContent = hexColor;
}

function updateSliderBackgrounds() {
  const {hue, saturation, lightness} = currentColor;
  
  // Telítettség slider háttér
  const satSlider = document.getElementById('satSlider');
  satSlider.style.background = `linear-gradient(to right, 
    hsl(${hue}, 0%, ${lightness}%), 
    hsl(${hue}, 100%, ${lightness}%))`;
  
  // Világosság slider háttér
  const lightSlider = document.getElementById('lightSlider');
  lightSlider.style.background = `linear-gradient(to right, 
    hsl(${hue}, ${saturation}%, 0%), 
    hsl(${hue}, ${saturation}%, 50%), 
    hsl(${hue}, ${saturation}%, 100%))`;
}

function hslToHex(h, s, l) {
  s /= 100;
  l /= 100;
  
  const c = (1 - Math.abs(2 * l - 1)) * s;
  const x = c * (1 - Math.abs((h / 60) % 2 - 1));
  const m = l - c / 2;
  
  let r = 0, g = 0, b = 0;
  
  if (h < 60) { r = c; g = x; b = 0; }
  else if (h < 120) { r = x; g = c; b = 0; }
  else if (h < 180) { r = 0; g = c; b = x; }
  else if (h < 240) { r = 0; g = x; b = c; }
  else if (h < 300) { r = x; g = 0; b = c; }
  else { r = c; g = 0; b = x; }
  
  r = Math.round((r + m) * 255);
  g = Math.round((g + m) * 255);
  b = Math.round((b + m) * 255);
  
  return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('').toUpperCase();
}

function addColorToSelection() {
  if (formData.colors.length >= 5) {
    alert('Maximum 5 színt választhatsz!');
    return;
  }
  
  const hex = hslToHex(currentColor.hue, currentColor.saturation, currentColor.lightness);
  
  if (!formData.colors.includes(hex)) {
    formData.colors.push(hex);
    renderStep2(document.getElementById('formContent'));
  } else {
    alert('Ez a szín már hozzá van adva!');
  }
}

function removeColor(hex) {
  formData.colors = formData.colors.filter(c => c !== hex);
  renderStep2(document.getElementById('formContent'));
}

function renderColorRectangle() {
  const container = document.getElementById('selectedColorsPreview');
  
  let html = '<div class="color-preview-box">';
  html += '<h3 class="preview-title">Kiválasztott színek előnézete</h3>';
  
  html += '<div class="color-rectangle-container">';
  
  // TÉGLALAP
  html += '<div class="color-rectangle">';
  formData.colors.forEach(hex => {
    html += `<div class="color-stripe" style="background: ${hex}"></div>`;
  });
  html += '</div>';
  
  // LISTA
  html += '<div class="color-list-side">';
  formData.colors.forEach(hex => {
    html += `
      <div class="color-item-side">
        <div class="color-mini-box" style="background: ${hex}"></div>
        <div class="color-info">
          <div class="color-hex">${hex}</div>
        </div>
        <button class="color-remove-mini" onclick="removeColor('${hex}')">×</button>
      </div>
    `;
  });
  html += '</div>';
  
  html += '</div>';
  
  // HARMONÍA
  if (formData.colors.length >= 2) {
    const harmony = calculateColorHarmony(formData.colors);
    html += `
      <div class="harmony-indicator ${harmony.class}">
        <div class="harmony-percent">${harmony.score}%</div>
        <div class="harmony-label">${harmony.label}</div>
        <div class="harmony-desc">${harmony.description}</div>
      </div>
    `;
  }
  
  html += '</div>';
  container.innerHTML = html;
}

function calculateColorHarmony(colors) {
  let totalScore = 0;
  let comparisons = 0;
  
  for (let i = 0; i < colors.length; i++) {
    for (let j = i + 1; j < colors.length; j++) {
      const score = compareColors(colors[i], colors[j]);
      totalScore += score;
      comparisons++;
    }
  }
  
  const avgScore = comparisons > 0 ? Math.round(totalScore / comparisons) : 0;
  
  let result = { score: avgScore, class: '', label: '', description: '' };
  
  if (avgScore >= 80) {
    result.class = 'excellent';
    result.label = 'Kiváló 🌟';
    result.description = 'Ezek a színek tökéletesen harmonizálnak!';
  } else if (avgScore >= 65) {
    result.class = 'good';
    result.label = 'Jó 👍';
    result.description = 'Ezek a színek jól passzolnak egymáshoz.';
  } else if (avgScore >= 50) {
    result.class = 'fair';
    result.label = 'Megfelelő 😊';
    result.description = 'Ezek a színek működnek együtt.';
  } else {
    result.class = 'poor';
    result.label = 'Gyenge 😕';
    result.description = 'Próbálj ki más színeket!';
  }
  
  return result;
}

function compareColors(hex1, hex2) {
  const rgb1 = hexToRgb(hex1);
  const rgb2 = hexToRgb(hex2);
  
  if (!rgb1 || !rgb2) return 50;
  
  const contrast = Math.abs(rgb1.r - rgb2.r) + Math.abs(rgb1.g - rgb2.g) + Math.abs(rgb1.b - rgb2.b);
  
  if (contrast >= 200 && contrast <= 400) return 90;
  else if (contrast >= 150 && contrast < 200) return 75;
  else if (contrast >= 100) return 60;
  else return 40;
}

function hexToRgb(hex) {
  const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}

// ============================================
// LÉPÉS 3: STÍLUS KÉPEKKEL
// ============================================

function renderStep3(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/stilus.png"></div>
      <h2 class="step-title">Milyen stílusú legyen?</h2>
      <p class="step-subtitle">Válassz egy vagy több stílust! Látni fogod a design példákat.</p>
    </div>
    
    <div class="style-grid" id="styleGrid"></div>
    
    <div id="stylePreviewContainer"></div>
    
    <div class="button-row">
      <button class="btn-secondary" onclick="prevStep()">◀ Vissza</button>
      <button class="btn-primary" onclick="nextStep()" ${formData.styles.length === 0 ? 'disabled' : ''}>Következő: Betűtípus ▶</button>
    </div>
  `;
  
  const grid = document.getElementById('styleGrid');
  STYLE_OPTIONS.forEach(style => {
    const card = document.createElement('div');
    card.className = 'style-card';
    card.setAttribute('data-id', style.id);
    if (formData.styles.includes(style.id)) card.classList.add('selected');
    
    card.innerHTML = `
      <div class="style-icon">${style.icon}</div>
      <div class="style-name">${style.name}</div>
      <div class="style-description">${style.description}</div>
      <div class="style-example">Például: ${style.example}</div>
    `;
    
    card.onclick = () => toggleStyle(style.id);
    grid.appendChild(card);
  });
  
  if (formData.styles.length > 0) {
    renderStyleImages();
  }
}

function toggleStyle(id) {
  if (formData.styles.includes(id)) {
    formData.styles = formData.styles.filter(s => s !== id);
  } else {
    formData.styles.push(id);
  }
  renderStep3(document.getElementById('formContent'));
}

function renderStyleImages() {
  const container = document.getElementById('stylePreviewContainer');
  
  const selectedStyles = STYLE_OPTIONS.filter(s => formData.styles.includes(s.id));
  
  let html = '<div class="style-images-preview">';
  html += '<h3 class="preview-title">Design előnézet</h3>';
  html += '<div class="style-images-grid">';
  
  selectedStyles.forEach(style => {
    html += `
      <div class="style-image-item">
        <div class="style-image-box">
          <img src="${style.imageUrl}" alt="${style.name}" onerror="this.style.display='none'">
        </div>
        <div class="style-image-label">${style.name}</div>
      </div>
    `;
  });
  
  html += '</div></div>';
  container.innerHTML = html;
}

// ============================================
// LÉPÉS 4: KÜLÖNBÖZŐ BETŰTÍPUSOK
// ============================================

function renderStep4(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/szoveg.png"></div>
      <h2 class="step-title">Legyen rajta szöveg?</h2>
    </div>
    
    <div class="text-choice">
      <button class="choice-btn ${formData.hasText === true ? 'active' : ''}" onclick="setHasText(true)">✓ Igen, legyen szöveg</button>
      <button class="choice-btn ${formData.hasText === false ? 'active' : ''}" onclick="setHasText(false)">✗ Nem, csak grafika</button>
    </div>
    
    <div id="textSection"></div>
    
    <div class="button-row">
      <button class="btn-secondary" onclick="prevStep()">◀ Vissza</button>
      <button class="btn-primary" onclick="nextStep()" ${formData.hasText === null ? 'disabled' : ''}>Következő: Inspirációk ▶</button>
    </div>
  `;
  
  if (formData.hasText === true) {
    renderTextSection();
  }
}

function setHasText(value) {
  formData.hasText = value;
  if (value === false) {
    formData.textContent = '';
    formData.favoriteFonts = [];
  }
  renderStep4(document.getElementById('formContent'));
}

function renderTextSection() {
  const section = document.getElementById('textSection');
  
  section.innerHTML = `
    <div class="text-input-group">
      <label>Mi legyen a szöveg?</label>
      <input type="text" class="text-input" id="textInput" placeholder="pl. Mountain Explorers" value="${formData.textContent}">
    </div>
    
    <div id="fontListContainer"></div>
    
    <div id="favoriteFontsContainer"></div>
  `;
  
  document.getElementById('textInput').oninput = (e) => {
    formData.textContent = e.target.value;
    if (e.target.value) renderFontList();
  };
  
  if (formData.textContent) renderFontList();
}

function renderFontList() {
  const container = document.getElementById('fontListContainer');
  
  container.innerHTML = '<h3 style="text-align: center; margin: 30px 0 20px; font-size: 18px; color: var(--text);">Próbálj ki betűtípusokat:</h3>';
  
  const fontList = document.createElement('div');
  fontList.className = 'font-list-large';
  
  FONT_OPTIONS.forEach(font => {
    const item = document.createElement('div');
    item.className = 'font-item-large';
    if (formData.favoriteFonts.includes(font.name)) item.classList.add('selected');
    
    item.innerHTML = `
      <div class="font-name-label">${font.name} - ${font.desc}</div>
      <div class="font-preview-large" style="font-family: ${font.family}; font-weight: ${font.weight}">
        ${formData.textContent}
      </div>
      <button class="font-like-btn ${formData.favoriteFonts.includes(font.name) ? 'liked' : ''}" onclick="toggleFont('${font.name}')">
        ${formData.favoriteFonts.includes(font.name) ? '❤️ Tetszik' : '♡ Tetszik?'}
      </button>
    `;
    
    fontList.appendChild(item);
  });
  
  container.appendChild(fontList);
  
  if (formData.favoriteFonts.length > 0) {
    renderFavoriteFonts();
  }
}

function toggleFont(fontName) {
  if (formData.favoriteFonts.includes(fontName)) {
    formData.favoriteFonts = formData.favoriteFonts.filter(f => f !== fontName);
  } else {
    formData.favoriteFonts.push(fontName);
  }
  renderFontList();
}

function renderFavoriteFonts() {
  const container = document.getElementById('favoriteFontsContainer');
  container.innerHTML = `
    <div class="favorite-fonts">
      <strong>Kedvenc fontjaid (${formData.favoriteFonts.length}):</strong>
      <div style="margin-top: 10px;">${formData.favoriteFonts.join(', ')}</div>
    </div>
  `;
}

// ============================================
// LÉPÉSEK 5-7: UGYANAZOK
// ============================================

function renderStep5(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/elkepzelesek.png"></div>
      <h2 class="step-title">Inspirációk & Elképzelések</h2>
      <p class="step-subtitle">Töltsd fel a képeidet vagy írj le bármit!</p>
    </div>
    
    <div class="upload-section">
      <label>📤 Töltsd fel a képeidet:</label>
      <input type="file" class="file-input" id="imageUpload" accept="image/*" multiple>
    </div>
    
    <div id="imagePreviewContainer"></div>
    
    <div class="upload-section">
      <label>💭 Írd le az elképzelésed:</label>
      <textarea class="textarea-input" id="descriptionInput" placeholder="pl. Szeretnék egy hegymászó témájú logót, ami minimalista, de figyelemfelkeltő...">${formData.description}</textarea>
    </div>
    
    <div class="upload-section">
      <label>🔗 Pinterest / Instagram linkek:</label>
      <input type="text" class="text-input" id="linksInput" placeholder="https://pinterest.com/..." value="${formData.links}">
    </div>
    
    <div class="button-row">
      <button class="btn-secondary" onclick="prevStep()">◀ Vissza</button>
      <button class="btn-primary" onclick="nextStep()">Következő: Kapcsolat ▶</button>
    </div>
  `;
  
  document.getElementById('imageUpload').onchange = handleImageUpload;
  document.getElementById('descriptionInput').oninput = (e) => formData.description = e.target.value;
  document.getElementById('linksInput').oninput = (e) => formData.links = e.target.value;
  
  if (formData.images.length > 0) renderImagePreview();
}

function handleImageUpload(e) {
  const files = Array.from(e.target.files);
  
  files.forEach(file => {
    const reader = new FileReader();
    reader.onload = (event) => {
      formData.images.push(event.target.result);
      formData.imageFiles.push(file);
      renderImagePreview();
    };
    reader.readAsDataURL(file);
  });
}

function renderImagePreview() {
  const container = document.getElementById('imagePreviewContainer');
  
  let html = '<div class="image-preview-grid">';
  
  formData.images.forEach((img, index) => {
    html += `
      <div class="image-preview-item">
        <img src="${img}" class="image-preview-img" alt="Kép ${index + 1}">
        <button class="image-remove-btn" onclick="removeImage(${index})">×</button>
      </div>
    `;
  });
  
  html += '</div>';
  container.innerHTML = html;
}

function removeImage(index) {
  formData.images.splice(index, 1);
  formData.imageFiles.splice(index, 1);
  renderImagePreview();
}

function renderStep6(container) {
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/kapcsolat.png"></div>
      <h2 class="step-title">Kapcsolat</h2>
      <p class="step-subtitle">Hogy tudjunk válaszolni neked!</p>
    </div>
    
    <div class="contact-form">
      <div class="form-group">
        <label>Neved: *</label>
        <input type="text" id="nameInput" placeholder="Nagy Péter" value="${formData.name}" required>
      </div>
      
      <div class="form-group">
        <label>E-mail: *</label>
        <input type="email" id="emailInput" placeholder="peter@gmail.com" value="${formData.email}" required>
      </div>
      
      <div class="form-group">
        <label>Telefon: (opcionális)</label>
        <input type="tel" id="phoneInput" placeholder="+36 30 123 4567" value="${formData.phone}">
      </div>
      
      <div class="form-group">
        <label>Egyéb megjegyzés:</label>
        <textarea id="notesInput" placeholder="Bármi amit még tudnunk kellene...">${formData.notes}</textarea>
      </div>
    </div>
    
    <div class="button-row">
      <button class="btn-secondary" onclick="prevStep()">◀ Vissza</button>
      <button class="btn-primary" onclick="validateAndNext()" id="nextBtn6">Összegzés ▶</button>
    </div>
  `;
  
  document.getElementById('nameInput').oninput = (e) => { formData.name = e.target.value; updateNextButton6(); };
  document.getElementById('emailInput').oninput = (e) => { formData.email = e.target.value; updateNextButton6(); };
  document.getElementById('phoneInput').oninput = (e) => formData.phone = e.target.value;
  document.getElementById('notesInput').oninput = (e) => formData.notes = e.target.value;
  
  updateNextButton6();
}

function updateNextButton6() {
  const btn = document.getElementById('nextBtn6');
  btn.disabled = !(formData.name && formData.email && validateEmail(formData.email));
}

function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateAndNext() {
  if (formData.name && formData.email && validateEmail(formData.email)) {
    nextStep();
  } else {
    alert('Kérlek töltsd ki a kötelező mezőket!');
  }
}

function renderStep7(container) {
  const selectedCategoryNames = formData.categories.map(id => CATEGORIES.find(c => c.id === id)?.name).filter(Boolean);
  const selectedStyleNames = formData.styles.map(id => STYLE_OPTIONS.find(s => s.id === id)?.name).filter(Boolean);
  
  container.innerHTML = `
    <div class="step-header">
      <div class="step-emoji"><img src="ikonok/projekt-terv.png"></div>
      <h2 class="step-title">Nézd át a projekt tervedet!</h2>
      <p class="step-subtitle">Ellenőrizd, hogy minden rendben van-e</p>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto;">
      
      <div class="summary-section">
        <h3>🎨 Mit terveztetsz:</h3>
        <p>${selectedCategoryNames.join(', ')}</p>
      </div>
      
      ${formData.colors.length > 0 ? renderColorsSummary() : ''}
      
      ${formData.styles.length > 0 ? `
        <div class="summary-section">
          <h3>✨ Stílus:</h3>
          <p>${selectedStyleNames.join(', ')}</p>
        </div>
      ` : ''}
      
      ${formData.hasText && formData.textContent ? `
        <div class="summary-section">
          <h3>✍️ Szöveg:</h3>
          <p><strong>"${formData.textContent}"</strong></p>
          ${formData.favoriteFonts.length > 0 ? `
            <p style="font-size: 14px; color: var(--text1); margin-top: 10px;">
              Kedvenc betűtípusok: ${formData.favoriteFonts.join(', ')}
            </p>
          ` : ''}
        </div>
      ` : ''}
      
      ${(formData.images.length > 0 || formData.description || formData.links) ? renderInspirationsSummary() : ''}
      
      <div class="summary-section">
        <h3>📧 Kapcsolat:</h3>
        <p><strong>Név:</strong> ${formData.name}</p>
        <p><strong>E-mail:</strong> ${formData.email}</p>
        ${formData.phone ? `<p><strong>Telefon:</strong> ${formData.phone}</p>` : ''}
        ${formData.notes ? `<p style="margin-top: 10px;"><strong>Megjegyzés:</strong> ${formData.notes}</p>` : ''}
      </div>
      
      <div class="privacy-checkbox">
        <label>
          <input type="checkbox" id="privacyCheckbox" required>
          <span>Elfogadom az adatvédelmi nyilatkozatot és hozzájárulok, hogy Móni feldolgozza az általam megadott adatokat a projekt megvalósítása érdekében.</span>
        </label>
      </div>
      
      <div class="button-row">
        <button class="btn-secondary" onclick="prevStep()">◀ Vissza módosításhoz</button>
        <button class="btn-submit" onclick="submitForm()">📧 KÜLDÉS MÓNINAK! 🚀</button>
      </div>
      
    </div>
  `;
}

function renderColorsSummary() {
  let html = '<div class="summary-section">';
  html += '<h3>🌈 Színek:</h3>';
  html += '<div class="summary-colors">';
  
  formData.colors.forEach(hex => {
    html += `
      <div class="summary-color-item">
        <div class="summary-color-box" style="background: ${hex}"></div>
        <div class="summary-color-name">${hex}</div>
      </div>
    `;
  });
  
  html += '</div></div>';
  return html;
}

function renderInspirationsSummary() {
  let html = '<div class="summary-section">';
  html += '<h3>🖼️ Inspirációk:</h3>';
  
  if (formData.images.length > 0) {
    html += `<p style="font-size: 14px; color: var(--text1); margin-bottom: 10px;">Feltöltött képek: ${formData.images.length} db</p>`;
    html += '<div class="summary-images">';
    
    formData.images.slice(0, 4).forEach(img => {
      html += `<img src="${img}" class="summary-image" alt="Inspiráció">`;
    });
    
    if (formData.images.length > 4) {
      html += `<div class="summary-more">+${formData.images.length - 4}</div>`;
    }
    
    html += '</div>';
  }
  
  if (formData.description) {
    html += `<p style="margin-top: 15px;"><strong>Leírás:</strong></p>`;
    html += `<p style="font-size: 15px; line-height: 1.6;">"${formData.description}"</p>`;
  }
  
  if (formData.links) {
    html += `<p style="margin-top: 10px;"><strong>Linkek:</strong></p>`;
    html += `<a href="${formData.links}" target="_blank" style="color: var(--accent); font-size: 14px;">${formData.links}</a>`;
  }
  
  html += '</div>';
  return html;
}

function submitForm() {
  const checkbox = document.getElementById('privacyCheckbox');
  
  if (!checkbox.checked) {
    alert('Kérlek fogadd el az adatvédelmi nyilatkozatot!');
    return;
  }
  
  const formDataToSend = new FormData();
  
  formDataToSend.append('name', formData.name);
  formDataToSend.append('email', formData.email);
  formDataToSend.append('phone', formData.phone);
  formDataToSend.append('categories', JSON.stringify(formData.categories));
  formDataToSend.append('colors', JSON.stringify(formData.colors));
  formDataToSend.append('styles', JSON.stringify(formData.styles));
  formDataToSend.append('hasText', formData.hasText);
  formDataToSend.append('textContent', formData.textContent);
  formDataToSend.append('favoriteFonts', JSON.stringify(formData.favoriteFonts));
  formDataToSend.append('description', formData.description);
  formDataToSend.append('links', formData.links);
  formDataToSend.append('notes', formData.notes);
  
  formData.imageFiles.forEach((file, index) => {
    formDataToSend.append(`image_${index}`, file);
  });
  
  const submitBtn = document.querySelector('.btn-submit');
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<div class="loader"></div> Küldés...';
  
  fetch('projekt-contact.php', {
    method: 'POST',
    body: formDataToSend
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showSuccessModal(data.project_id || 'SD2025-XXX');
    } else {
      showErrorModal(data.message || 'Hiba történt a küldés során.');
    }
  })
  .catch(error => {
    console.error('Hiba:', error);
    showErrorModal('Hálózati hiba történt. Kérlek próbáld újra!');
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = '📧 KÜLDÉS MÓNINAK! 🚀';
  });
}

function showSuccessModal(projectId) {
  const modal = document.getElementById('successModal');
  document.getElementById('projectId').textContent = '#' + projectId;
  modal.style.display = 'flex';
}

function showErrorModal(message) {
  const modal = document.getElementById('errorModal');
  document.getElementById('errorMessage').textContent = message;
  modal.style.display = 'flex';
}

function closeErrorModal() {
  document.getElementById('errorModal').style.display = 'none';
}