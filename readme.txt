# RSS pro Seznam Newsfeed

**Autor:** Tomáš Rohlena / SEOTEST.CZ / Webmint s.r.o.  
**Verze:** 1.1  
**Licence:** GNU General Public License v2 nebo novější  
**Požadavky:** WordPress 5.0+  
**Jazyk:** 🇨🇿 Čeština

## Popis

Tento plugin vytvoří vlastní RSS výstup na specifické adrese (např. `/rss_newsfeed_seznam`), který splňuje požadavky pro zařazení obsahu do Seznam Newsfeedu.

Výstup RSS obsahuje:
- Posledních 20 příspěvků
- `<enclosure>` s featured image
- `<description>` buď z excerptu nebo část obsahu (první věty)
- Validní `<pubDate>` ve formátu RSS

## Funkce

- Výběr, zda preferovat `excerpt` nebo část obsahu
- Možnost nastavit počet vět v popisu
- Výběr kategorií pro zahrnutí a vyloučení
- Vyloučení příspěvků podle ID
- Možnost upravit URL výstupu RSS
- Administrace přímo v nastavení WordPressu

## Instalace

1. Nahrajte složku `rss-newsfeed-seznam` do adresáře `wp-content/plugins/`
2. Aktivujte plugin v administraci WordPressu
3. Přejděte do `Nastavení > RSS Newsfeed Seznam` a nakonfigurujte plugin
4. RSS výstup bude dostupný na URL, kterou nastavíte

## Licence

Tento plugin je publikován pod [GNU General Public License v2 nebo novější](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
