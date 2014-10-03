<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
if($tag === 'sitemap') { 
    echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
} else { 
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
} 
if(is_array($urlList)) {
    foreach($urlList as $url) { 
        echo '<' . $tag . '>';
        echo '<loc>' . $route . $url . '</loc>';
        echo '</' . $tag . '>';
    }
} else {
    echo '<' . $tag . '>';
    echo '<loc>' . $route . $urlList . '</loc>';
    echo '</' . $tag . '>';
}
if($tag === 'sitemap') { 
    echo '</sitemapindex>';
} else { 
    echo'</urlset>';
} 
