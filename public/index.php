<?php
require_once __DIR__ . '/../inc/config.php';

// show latest news
$stmt = $pdo->query('SELECT * FROM news WHERE is_published = 1 ORDER BY created_at DESC LIMIT 5');
$news = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LMS Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
  <style>
    body {
      box-sizing: border-box;
    }
    * {
      font-family: 'DM Sans', sans-serif;
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: '#1A3263',
            slate: '#547792',
            amber: '#FAB95B',
            cream: '#E8E2DB'
          }
        }
      }
    }
  </script>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body class="h-full bg-cream text-navy">
  <div class="h-full w-full overflow-auto"><!-- Header -->
   <header class="bg-navy">
    <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
     <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-amber rounded-lg flex items-center justify-center">
       <svg class="w-6 h-6 text-navy" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
       </svg>
      </div>
      <h1 id="site-title" class="text-xl font-semibold text-cream">Learning Management System</h1>
     </div><a href="<?= BASE_URL ?>/public/login.php" class="bg-amber hover:bg-amber/90 text-navy font-medium px-5 py-2.5 rounded-lg transition-colors"> Logini20 </a>
    </div>
   </header><!-- Main Content -->
   <main class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid lg:grid-cols-3 gap-8"><!-- News Section -->
     <div class="lg:col-span-2">
      <h2 id="news-heading" class="text-2xl font-semibold mb-6 flex items-center gap-2"><span class="w-1.5 h-6 bg-amber rounded-full"></span> News &amp; Updates</h2>
      <div class="space-y-4" id="news-container"><!-- News Card 1 -->
       <article class="bg-white rounded-xl p-6 shadow-sm border border-slate/10 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between gap-4">
         <div class="flex-1">

   <?php foreach($news as $n): ?>
         <h6><?= htmlspecialchars($n['title']) ?></h6>
            <p><?= nl2br(htmlspecialchars(substr($n['body'],0,300))) ?></p>
            <small class="text-muted"><?= htmlspecialchars($n['created_at']) ?></small>
         </div><span class="bg-amber/20 text-navy text-xs font-medium px-3 py-1 rounded-full whitespace-nowrap">New</span>
        </div>
<?php endforeach; ?>


        <div class="mt-4 pt-4 border-t border-slate/10"><time class="text-sm text-slate"></time>
        </div>
       </article>
      </div>
     </div><!-- Action Buttons -->
     <div class="lg:col-span-1 flex flex-col gap-3"><a href="<?= BASE_URL ?>/public/courses.php" class="bg-amber hover:bg-amber/90 text-navy font-medium px-6 py-3 rounded-lg transition-colors text-center">Browse Courses</a> 
     <a  href="<?= BASE_URL ?>/public/register.php" class="bg-navy hover:bg-navy/90 text-cream font-medium px-6 py-3 rounded-lg transition-colors text-center border border-navy">Register</a>
     </div>
    </div>
   </main><!-- Footer -->
   <footer class="bg-navy/5 border-t border-slate/10 mt-auto">
    <div class="max-w-6xl mx-auto px-6 py-6 text-center text-slate text-sm">
     © 2026 Learning Management System. All rights reserved.
    </div>
   </footer>
  </div>
  <script>
    const defaultConfig = {
      site_title: 'Learning Management System',
      news_heading: 'News & Updates',
      background_color: '#E8E2DB',
      header_color: '#1A3263',
      accent_color: '#FAB95B',
      text_color: '#1A3263',
      secondary_color: '#547792'
    };

    async function onConfigChange(config) {
      const siteTitle = document.getElementById('site-title');
      const newsHeading = document.getElementById('news-heading');

      if (siteTitle) {
        siteTitle.textContent = config.site_title || defaultConfig.site_title;
      }
      if (newsHeading) {
        newsHeading.innerHTML = `<span class="w-1.5 h-6 bg-amber rounded-full"></span>${config.news_heading || defaultConfig.news_heading}`;
      }
    }

    function mapToCapabilities(config) {
      return {
        recolorables: [
          {
            get: () => config.background_color || defaultConfig.background_color,
            set: (value) => { config.background_color = value; window.elementSdk.setConfig({ background_color: value }); }
          },
          {
            get: () => config.header_color || defaultConfig.header_color,
            set: (value) => { config.header_color = value; window.elementSdk.setConfig({ header_color: value }); }
          },
          {
            get: () => config.text_color || defaultConfig.text_color,
            set: (value) => { config.text_color = value; window.elementSdk.setConfig({ text_color: value }); }
          },
          {
            get: () => config.accent_color || defaultConfig.accent_color,
            set: (value) => { config.accent_color = value; window.elementSdk.setConfig({ accent_color: value }); }
          },
          {
            get: () => config.secondary_color || defaultConfig.secondary_color,
            set: (value) => { config.secondary_color = value; window.elementSdk.setConfig({ secondary_color: value }); }
          }
        ],
        borderables: [],
        fontEditable: {
          get: () => config.font_family || 'DM Sans',
          set: (value) => { config.font_family = value; window.elementSdk.setConfig({ font_family: value }); }
        },
        fontSizeable: {
          get: () => config.font_size || 16,
          set: (value) => { config.font_size = value; window.elementSdk.setConfig({ font_size: value }); }
        }
      };
    }

    function mapToEditPanelValues(config) {
      return new Map([
        ['site_title', config.site_title || defaultConfig.site_title],
        ['news_heading', config.news_heading || defaultConfig.news_heading]
      ]);
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities,
        mapToEditPanelValues
      });
    }
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c4e6c63825abc43',t:'MTc2OTU4MTY0MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>