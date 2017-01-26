wikidata-game-flower-color
==========================

A mini-game for the [distributed wikidata game](https://tools.wmflabs.org/wikidata-game/distributed/).
It helps to assign the flower color property [P2827](https://www.wikidata.org/wiki/Property:P2827) to plants in [wikidata](https://www.wikidata.org/wiki/Wikidata:Main_Page).

### Database
#### Colors
The game database contains colors already used for flower colors in [TraitBank](http://eol.org/traitbank).
The list of colors can be found in [colors.tsv](data/colors.tsv).

#### Plants
The plant database consists of wikidata items that are child taxa of flowering plants (angiosperms) at the species level that have a scientific name, a ncbi taxid and at least one image.
The SPARQL to get the list is:
```sparql
SELECT ?item ?sciname ?ncbi WHERE {
  ?item (wdt:P171*) wd:Q25314 .
  ?item wdt:P685 ?ncbi .
  ?item wdt:P225 ?sciname .
  FILTER EXISTS { ?item wdt:P18 ?picture }
  FILTER EXISTS { ?item wdt:P105 wd:Q7432 }
}
```

### Logo
The [logo](https://cdn.pixabay.com/photo/2016/01/21/19/57/marguerite-1154604_960_720.jpg)
is a [free image](https://pixabay.com/en/service/terms/#usage)
([CC-0](https://creativecommons.org/publicdomain/zero/1.0/deed.en))
from [pixaby.com](https://pixabay.com)
uploaded by user [Dieter_G](https://pixabay.com/en/users/Dieter_G-359839/).
