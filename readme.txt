=== RSS pro Seznam Newsfeed ===

Author:						 Tomáš Rohlena / SEOTEST.CZ / Webmint s.r.o. 
Contributors:      seotest23 
Tested up to:      6.7.2
Stable tag:        1.1
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Tags:              plugin rss, plugin newsfeed

The plugin will create an RSS/XML feed according to your filtering, which is in the ideal format for Seznam.cz Newsfeed.


== Description ==

Tento plugin vytvoří vlastní RSS výstup na specifické adrese (např. `/rss_newsfeed_seznam`), který splňuje požadavky pro zařazení obsahu do Seznam Newsfeedu.

Výstup RSS obsahuje:
- Posledních 20 příspěvků
- `<enclosure>` s featured image
- `<description>` buď z excerptu nebo část obsahu (první věty)
- Validní `<pubDate>` ve formátu RSS

- Výběr, zda preferovat `excerpt` nebo část obsahu
- Možnost nastavit počet vět v popisu
- Výběr kategorií pro zahrnutí a vyloučení
- Vyloučení příspěvků podle ID
- Možnost upravit URL výstupu RSS
- Administrace přímo v nastavení WordPressu

== Installation ==

1. Nahrajte složku `rss-newsfeed-seznam` do adresáře `wp-content/plugins/`
2. Aktivujte plugin v administraci WordPressu
3. Přejděte do `Nastavení > RSS Newsfeed Seznam` a nakonfigurujte plugin
4. RSS výstup bude dostupný na URL, kterou nastavíte

=== License ===

Tento plugin je publikován pod [GNU General Public License v2 nebo novější](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
