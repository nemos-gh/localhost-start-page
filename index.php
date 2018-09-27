<?php
// Get cookie values (used for Settings) 
$sort_by_name = $_COOKIE["sort_by_name"];
$colors_predefined = $_COOKIE["colors_predefined"];

// Check OS 
if( strtoupper(PHP_OS) === 'WINDOWS' ) 
  $shell_cmnd = $sort_by_name ? 'dir' : 'dir /T';
else 
  $shell_cmnd = $sort_by_name ? 'ls' : 'ls -t';

// Execute shell command depending on OS 
exec($shell_cmnd, $output);
// Remove 'index.php' from $output
unset($output[array_search('index.php', $output)]);
// Reindex array from zero
$output = array_values($output);


// Available block colors [bg, text]
$colors = array(
  'blue'    => ['#0074D9', '#ffdc00'],
  'navy'    => ['#001f3f', '#ff4136'],
  'aqua'    => ['#7FDBFF', '#85144b'],
  'teal'    => ['#39CCCC', '#001f3f'],
  'olive'   => ['#3D9970', '#ffffff'],
  'green'   => ['#2ECC40', '#001f3f'],
  'yellow'  => ['#FFDC00', '#0074D9'],
  'orange'  => ['#FF851B', '#001f3f'],
  'red'     => ['#FF4136', '#ffffff'],
  'maroon'  => ['#85144b', '#ff851b'],
  'lime'    => ['#01FF70', '#85144b'],
  'gray'    => ['#aaaaaa', '#111111']
);
// Array of colors names from $colors keys
$colors_names = array_keys($colors);
?>

<!doctype html>
<html lang="en">
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>All Works</title>

  <style>
    /* Basic */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Helvetica', sans-serif;
      background: #303030;
    }
    ul {
      margin: 0;
      padding: 0;
      list-style: none;
    }
    div,
    label,
    input,
    textarea,
    button,
    select,
    a {
      -webkit-tap-highlight-color: rgba(0,0,0,0);
    }

    /* List of block elements */
    ul#works {
      display: flex;
      flex-wrap: wrap;
    }
    ul#works li {
      display: flex;
      width: 50vw;
      height: 33.3333vh;
      overflow: hidden;
    }
    ul#works li a {
      display: flex;
      justify-content: center;
      align-items: center;
      min-width: 100%;
      min-height: 100%;
      color: inherit;
      font-size: 1.5em;
      font-weight: 700;
      text-decoration: none;
      transform: scale(1);
      transition: transform 0.4s linear;
      transition-delay: 0.1s;
      white-space: nowrap;
    }
    ul#works a:hover {
      transform: scale(1.8);
      transition-duration: 0.25s;
      transition-delay: 0s;
    }

    /* Page bg text */
    .bg-text {
      position: fixed;
      top: 0;
      display: flex;
      justify-content: center;
      align-items: flex-end;
      width: 100vw;
      height: 100vh;
      z-index: -1;
    }
    .bg-text span {
      margin-bottom: 40px;
      color: rgba(255,255,255,0.03);
      font-size: 5.7em;
      font-weight: 700;
      -webkit-user-select: none;
         -moz-user-select: none;
              user-select: none;
    }

    /* Settings */
    .btn-settings {
      position: fixed;
      top: 12px;
      left: 10px;
      line-height: 0;
      z-index: 1;
    }
    .btn-settings .gear {
      position: relative;
      width: 26px;
      height: 26px;
      fill: rgba(0,0,0,0.4);
    }
    .btn-settings:hover .gear {
      animation: spin 1s infinite linear;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(180deg); }
    }
    .settings-overlay {
      visibility: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      opacity: 0;
      background: rgba(255,255,255,0.9);
      transition: all 0.2s linear;
      cursor: pointer;
      z-index: 100;
    }
    .settings-overlay.show {
      visibility: visible;
      opacity: 1;
    }
    .settings {
      margin-bottom: 100px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 80px rgba(0,0,0,0.2);
      -webkit-user-select: none;
         -moz-user-select: none;
              user-select: none;
      cursor: auto;
      overflow: hidden;
    }
    .settings h2 {
      margin: 0;
      padding: 25px 0 23px;
      color: white;
      text-align: center;
      letter-spacing: 1px;
      background: #111111;
    }
    .settings li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 32px 40px;
      border-bottom: 1px dashed #e0e0e0;
    }
    .settings li:last-child {
      border-bottom: 0;
    }
    .settings li span {
      /* margin-top: 8px; */
      margin-right: 40px;
      color: #424345;
      font-size: 1em;
      font-weight: 700;
      text-transform: uppercase;
    }
    .settings li span i {
      display: block;
      margin-top: 2px;
      color: #b7b7b7;
      font-size: 13px;
      font-style: normal;
      font-weight: normal;
      text-transform: none;
    }

    /* Slide buttons */
    .settings .switch {
      position: relative;
      display: inline-block;
      position: relative;
      width: 46px;
      height: 26px;
      cursor: pointer;
    }
    .settings .switch input {
      display: none;
    }
    .settings .switch .slide {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: tomato;
      transition: all 0.2s ease-in-out;
      transition-delay: 0.1s;
      border-radius: 26px;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    .settings .switch .slide::before {
      content: '';
      position: absolute;
      top: 2px;
      left: 2px;
      width: 22px;
      height: 21px;
      background: ghostwhite;
      transition: all 0.15s ease-in-out;
      border-top: 1px solid white;
      border-radius: 50%;
      box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .settings .switch input:checked + .slide {
      background: mediumseagreen;
    }
    .settings .switch input:checked + .slide::before {
      left: calc(100% - 24px);
    }

    /* Media Queries */
    @media (min-width: 576px) {
      .bg-text span { font-size: 9em; margin-bottom: 0px; }
    }
    @media (min-width: 768px) {
      ul#works li a { font-size: 2.5em; }
      .bg-text span { font-size: 13em; margin-bottom: -40px; }
      .btn-settings { top: 15px; left: 15px; }
      .btn-settings .gear { width: 32px; height: 32px; }
      .settings li span { margin-right: 100px; }
    }
    @media (min-width: 992px) {
      .bg-text span { font-size: 16em; margin-bottom: -60px; }
    }
    @media (min-width: 1200px) {
      .bg-text span { font-size: 21em; margin-bottom: -80px; }
    }
    @media (min-width: 1600px) {
      .bg-text span { font-size: 25em; margin-bottom: -100px; }
    }
  </style>
</head>
<body>

  <!-- START SETTINGS -->
  <a class="btn-settings" href="#">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="gear"><path d="M24 13.616v-3.232c-1.651-.587-2.693-.752-3.219-2.019v-.001c-.527-1.271.1-2.134.847-3.707l-2.285-2.285c-1.561.742-2.433 1.375-3.707.847h-.001c-1.269-.526-1.435-1.576-2.019-3.219h-3.232c-.582 1.635-.749 2.692-2.019 3.219h-.001c-1.271.528-2.132-.098-3.707-.847l-2.285 2.285c.745 1.568 1.375 2.434.847 3.707-.527 1.271-1.584 1.438-3.219 2.02v3.232c1.632.58 2.692.749 3.219 2.019.53 1.282-.114 2.166-.847 3.707l2.285 2.286c1.562-.743 2.434-1.375 3.707-.847h.001c1.27.526 1.436 1.579 2.019 3.219h3.232c.582-1.636.749-2.69 2.027-3.222h.001c1.262-.524 2.12.101 3.698.851l2.285-2.286c-.743-1.563-1.375-2.433-.848-3.706.527-1.271 1.588-1.44 3.221-2.021zm-12 3.384c-2.762 0-5-2.239-5-5s2.238-5 5-5 5 2.239 5 5-2.238 5-5 5zm3-5c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3z"/></svg>
  </a>

  <div class="settings-overlay">
    <div class="settings">
      <h2>Settings</h2>
      <ul>
        <li>
          <span>Sort by date<i>Turn off to sort by Name</i></span>
          <label class="switch">
            <input id="sortBy" name="sortBy" type="checkbox" checked>
            <div class="slide"></div>
          </label>
        <li>
          <span>Random colors<i>Turn off for Predefined Colors</i></span>
          <label class="switch">
            <input id="rndColors" name="rndColors" type="checkbox" checked>
            <div class="slide"></div>
          </label>
        </li>
      </ul>
    </div>
  </div>
  <!-- END SETTINGS -->

  <!-- START BLOCKS -->
  <?php
  $colors_used = [];
  
  // Start ul list of block elements
  echo '<ul id="works">';
  foreach ($output as $key => $value) {

    // If output has more elements than the number of available colors, 
    // empty the array of used colors to use again 
    if($key !== 0 && ! ($key % count($colors_names))) $colors_used = [];

    // Pick new color
    if($colors_predefined) {
      $current_color = $colors_names[$key];
    }
    else {
      // Pick unique random color
      do {
        $current_color = array_rand($colors);
      } while (in_array($current_color, $colors_used));
      
      // Add new color to used colors 
      array_push($colors_used, $current_color);
    }

    // Add new block element with unique color
    echo '<li style="background: ' . $colors[$current_color][0]
    . '; color: ' . $colors[$current_color][1]
    . '"><a href="/' . $value . '">'
    . ucfirst($value) 
    . '</a></li>';
  }
  echo '</ul>';
  ?>
  <!-- END BLOCKS -->

  <!-- Background text -->
  <div class="bg-text">
    <span>localhost</span>
  </div>


  <!-- Start JavaScript -->
  <script>
    var btnSettings = document.querySelector('.btn-settings');
    var stgsOverlay = document.querySelector('.settings-overlay');
    var stgsPanel = document.querySelector('.settings');
    var currentCookie = document.cookie;

    // Show the settings 
    btnSettings.addEventListener('click', function() {
      stgsOverlay.classList.add('show');
    })

    // Close the settings and reload the page
    stgsOverlay.addEventListener('click', function(e) {
      this.classList.remove('show');
      if(currentCookie !== document.cookie) location.reload();
    })
    stgsPanel.addEventListener('click', function(e) {
      e.stopPropagation();
    })

    // Set/remove cookie on checkbox event 
    var chkSortBy = document.getElementById('sortBy');
    var chkRndColors = document.getElementById('rndColors');

    chkSortBy.addEventListener('click', function(e) {
      updateCookie(e.target, 'sort_by_name');
    })

    chkRndColors.addEventListener('click', function(e) {
      updateCookie(e.target, 'colors_predefined');
    })

    // Check cookies on page load and apply to checkboxes
    var sortByName = document.cookie.indexOf('sort_by_name');
    var colorsDefinite = document.cookie.indexOf('colors_predefined');

    if(sortByName !== -1) chkSortBy.checked = false;
    if(colorsDefinite !== -1) chkRndColors.checked = false;

    // Cookies' functions
    var updateCookie = function(checkBox, name) {
      if( ! checkBox.checked ) setCookie(name)
      else removeCookie(name);
    }

    var setCookie = function(name) {
      var t = new Date();
      t.setTime(t.getTime() + 1000*60*60*24*365*5);
      t = t.toUTCString();
      document.cookie = name + '=1; expires=' + t + '; path=/;';
    }

    var removeCookie = function(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
  </script>
  <!-- End JavaScript -->
</body>
</html>