* nav articole cu titlu si next/prev
* ~~pagina Despre si Updates~~
* cache pagina Editii


FUNCTIONALITATI NOI
======================================
* **Search** - de implementat complex search
* **Image Gallery** - plugin pentru vizualizare imagini (preferabil mobile-friendly)
* **Download CSV** baza de date, eventual cu opţiuni de personalizare
* Colectie de citate de folosit pentru Site Tagline si Search Placeholder
* Checkboxes pentru a semnala date incorecte/incomplete pentru informatiile din baza de date
* RSS (?)


IMBUNATATIRI FUNCTIONALITATI EXISTENTE
======================================

Search
------------------
* numele autorilor sunt linkuri către un search pentru toate articolele acelui autor
* ~~fiecare rand/card e link către editie si articol (deschide prima pagină)~~
* completare termen de cautare in caseta de cautare, in pagina cu rezultate
* ~~adauga note la fiecare review~~

Articole
------------------
* info pe scurt despre editie:
  ** joc full
  ** ~~link revistevechi wiki~~
* navigare editii - coperta (microthumb) pentru editiile prev/next
* navigare articole
  ** de terminat (prev/next)
  ** ~~adauga titlu articol~~
* posibilitate afisare pagini duble (cu cover page separat)
* ~~buton/link pentru a incarca din nou toate paginile revistei (home)~~
* ~~link pe div articol doar catre articol id, nu prima pagina din articol~~

Editii
------------------
* coperta pentru fiecare editie, chiar si cele nescanate
* incarcare imagini via ajax (pana la instalarea unui Image Gallery)
* ~~filtru ani~~ - luna (nu e cazul deocamdata)
* ~~Linkuri download pentru fiecare revista~~
* ~~Linkuri revistevechi.awiki.ro~~

Layout, grafica and stuff
-------------------------
* Layout coerent cap-coada
* Imbunatatire footer
* Logo (+responsive)
* Favicon
* Titlu pagini - sa reprezinte pagina curenta
* top banner (logo+search) always on top
* Reenable footer & fix poziție

Diverse
------------------
* Incarcare arhiva imagini pe un filesharing si link in README


IMBUNATATIRI COD
================================================

Diverse
------------------
* Error handling, logging
* clasa Helper care sa centralizeze fisierele helper
* Separare mai bună MCV
* namespaces + autoloader
* Fix TODOs cod
* Documentatie in cod la functiile fara phpdoc, unde e cazul
* Remove Google fonts

Performanta
-----------
* Caching
* Optimizare constructie obiecte

Articole
------------------
* Header navigatie editii
  ** construire linkuri din editii fara id
  ** implementare first & last
* Header - linkurile de download sa fie in ordinea Reviste, DVD, CD1, CD2, 
* ~~pune link pe card, nu doar imagine~~
* ~~margine la primul thumb, pentru a fi afisate spreads corect la coloane pare~~

Editii
------------
* adauga metode getNextEditieLink/getPrevEditieLink
* pune link pe card, nu pe imagine
* adauga imaginea copertii ca background la card, nu imagine in <div>
* adauga Revista in constructor si foloseste Revista->revistaDirPath
  in loc de buildHomeDirPath();
* metoda getLuna() care sa trateze si lunile duble (pentru titlu)
* tratament special Level nr. mai 1998 - sa afiseze comentariul din DB

Revista
-------
* de completat clasa si folosit in clasa Editie

DBC
------------------
* modifica toate query-urile DB sa foloseasca constantele
* prepare statements (escape)
* sanitize inputs

Search
------------------
* foloseste div.search-container pentru a tine rezultate (duh!)

CSS
------------------
* reorganizare clase, acum e varza
* max-width mai mic la editii (pagina editii.php)
* egalizare imagini thumbnails
* optimizare ecrane diferite (IN PROGRESS)
* ~~huge thumb sparge layoutul pe mobil~~
* ~~microthumbs la search results - trebuie margin-left~~


BUGS
======================================
* Editiile construite in simpleSearch sunt de tip PREVIEW si nu se genereaza corect atributul areCuprins
* La unele rezultate de cautari nu apare nici pagina default (ex: "Level" -> rezultatul 2010-10)
(vezi si comentariu in Articol.php -> getHtmlOutput)
* Cautare dupa Sam & Max nu a avut ca rezultat si o editie, desi a fost joc full
* Cautarea "joc full" nu a returnat rubricile joc full 
* footer - fix pentru afisare jos


DATABASE
======================================
* de tratat articolele cu reclame intercalate (ex: News 3 pg, apoi reclama, apoi iar news 2 pg)
* DB versioning
* fix articole fara pg_count (vezi comentariu in Articol.php -> getHtmlOutput)
* fix count (2 in loc de 1) pentru Cuprins Level 2002 - 2005 (probabil si ulterior) 

Rescan
------------------
2002-07 - regenerate to higher-res
2002-09 - idem