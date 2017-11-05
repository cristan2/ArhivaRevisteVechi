
FUNCTIONALITATI NOI
======================================
* **Search** - de implementat complex search
* **Image Gallery** - plugin pentru vizualizare imagini (preferabil mobile-friendly)
* **Download CSV** baza de date, eventual cu opţiuni de personalizare
* Colectie de citate de folosit pentru Site Tagline si Search Placeholder
* Checkboxes pentru a semnala date incorecte/incomplete pentru informatiile din baza de date


IMBUNATATIRI FUNCTIONALITATI EXISTENTE
======================================

Search
------------------
* la articole, pune link catre editia din care face parte articolul
* numele autorilor sunt linkuri către un search pentru toate articolele acelui autor
* fiecare rand/card e link către articol (deschide prima pagină)

Articole
------------------
* info pe scurt despre editie:
  ** joc full
  ** link revistevechi wiki
* navigare editii - coperta pentru editiile prev/next
* buton/link pentru a incarca din nou toate paginile revistei (home)

Editii
------------------
* coperta pentru fiecare editie, chiar si cele nescanate
* incarcare imagini via ajax (pana la instalarea unui Image Gallery)
* Linkuri download pentru fiecare revista
* Linkuri revistevechi.awiki.ro

Layout, grafica and stuff
-------------------------
* Layout coerent cap-coada
* Footer
* Logo
* Favicon
* Titlu pagini - sa reprezinte pagina curenta

Diverse
------------------
* Incarcare arhiva imagini pe un filesharing si link in README


IMBUNATATIRI COD
================================================

Diverse
------------------
* clasa Helper care sa centralizeze fisierele helper
* Separare mai bună MCV
* namespaces + autoloader
* error handling
* Fix TODOs cod

ARTICOLE - general
------------------
* Header navigatie editii
  ** construire linkuri din editii fara id
  ** implementare first & last
* Linkurile de download sa fie in ordinea Reviste, DVD, CD1, CD2, 

ARTICOLE - page viewer
------------------
* de terminat navigatie articole
* posibilitate afisare pagini duble (cu cover page separat)

EDITII
------------------
* adauga metode getNextEditieLink/getPrevEditieLink
* pune link pe card, nu doar imagine
* adauga Revista in constructor si foloseste Revista->revistaDirPath
  in loc de buildHomeDirPath();
* metoda getLuna() care sa trateze si lunile duble
* tratament special Level nr. mai 1998

REVISTA
------------------
* de completat clasa si folosit in clasa Editie

DBC
------------------
* modifica toate query-urile DB sa foloseasca constantele
* prepare statements (escape)
* sanitize inputs

CSS
------------------
* reorganizare clase, acum e varza
* max-width mai mic la editii (pagina editii.php)
* egalizare imagini thumbnails


BUGS
======================================
* Editiile construite in simpleSearch sunt de tip PREVIEW si nu se genereaza corect atributul areCuprins
* La unele rezultate de cautari nu apare nici pagina default (ex: "Level" -> rezultatul 2010-10)


DATABASE
======================================
* de tratat articolele cu reclame intercalate (ex: News 3 pg, apoi reclama, apoi iar news 2 pg)

Rescan
------------------
2002-07 - upscale
2002-09 - idem