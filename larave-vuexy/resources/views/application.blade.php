<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="{{ asset('icon.ico') }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CMP</title>
  <script>
    var version = <?php $version = trim(file_get_contents(storage_path('version.txt')));
echo $version; ?>;
    localStorage.setItem('appVersion', version);
  </script>
  <link rel="stylesheet" type="text/css" href="{{ asset('loader.css') }}?version={{ $version }}" />
  @vite(['resources/ts/main.ts'])
</head>

<body>
  <div id="app">
    <div id="loading-bg">
      <div class="loading-logo">
        <!-- SVG Logo -->
        <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="50.000000pt" height="30.000000pt"
          viewBox="0 0 761.000000 500.000000" preserveAspectRatio="xMidYMid meet">

          <g transform="translate(0.000000,500.000000) scale(0.100000,-0.100000)" fill="#4884F3" stroke="none">
            <path d="M40 4695 l0 -205 3760 0 3760 0 0 205 0 205 -3760 0 -3760 0 0 -205z" />
            <path d="M1353 3910 c-275 -28 -480 -157 -586 -367 -85 -171 -82 -132 -82
                -1098 l0 -850 23 -79 c38 -135 87 -221 177 -312 157 -158 373 -227 659 -211
                318 18 538 146 649 378 63 131 78 209 85 426 4 159 2 186 -12 208 l-16 25
                -220 0 c-207 0 -220 -1 -230 -19 -5 -11 -10 -77 -10 -148 0 -204 -30 -304
                -110 -377 -62 -56 -102 -70 -200 -70 -66 -1 -95 4 -131 20 -55 26 -111 81
                -137 138 -41 87 -43 128 -40 916 l3 755 24 59 c57 142 185 212 341 184 108
                -19 182 -83 222 -194 18 -49 22 -88 26 -227 4 -156 6 -168 25 -182 17 -12 62
                -15 224 -15 259 0 243 -12 243 178 0 305 -47 471 -177 630 -77 94 -244 185
                -393 215 -99 20 -255 27 -357 17z" />
            <path d="M2688 3869 c-17 -9 -18 -87 -18 -1414 0 -1378 0 -1404 19 -1415 26
                -13 386 -13 412 0 19 10 19 33 19 1032 0 562 4 1018 8 1013 5 -6 96 -462 202
                -1015 126 -661 197 -1011 208 -1022 13 -16 37 -18 202 -18 136 0 191 3 204 13
                14 10 57 219 212 1022 106 555 196 1014 198 1019 2 6 5 -447 5 -1006 1 -910 3
                -1018 17 -1032 23 -24 405 -24 428 0 14 14 16 155 16 1409 l0 1394 -22 15
                c-19 14 -68 16 -335 16 -336 0 -336 0 -348 -53 -11 -52 -365 -1869 -366 -1880
                -2 -42 -37 127 -199 963 -153 786 -185 938 -202 953 -18 15 -49 17 -332 17
                -192 0 -318 -4 -328 -11z" />
            <path d="M5233 3865 l-23 -16 0 -1398 c0 -1073 3 -1400 12 -1409 18 -18 481
                -17 497 1 8 10 12 183 13 583 l3 569 240 6 c266 7 383 25 524 79 316 122 447
                394 420 869 -11 186 -62 341 -149 457 -70 92 -217 182 -361 219 -162 43 -212
                46 -689 51 -419 5 -467 4 -487 -11z m842 -395 c155 -22 230 -67 275 -161 79
                -168 59 -494 -37 -600 -76 -84 -170 -109 -415 -109 l-168 0 0 433 c0 239 3
                437 7 440 10 11 259 8 338 -3z" />
            <path d="M40 285 l0 -205 3760 0 3760 0 0 205 0 205 -3760 0 -3760 0 0 -205z" />
          </g>
        </svg>
      </div>
      <div class=" loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
  </div>

  <script>
    const loaderColor = localStorage.getItem('vuexy-initial-loader-bg') || '#FFFFFF'
    const primaryColor = '#4884F4'

    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)
    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)

    if (primaryColor)
      document.documentElement.style.setProperty('--initial-loader-color', primaryColor)

  </script>
</body>

</html>
