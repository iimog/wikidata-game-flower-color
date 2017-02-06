wikidata-game-flower-color
==========================

[![Build Status](https://secure.travis-ci.org/iimog/wikidata-game-flower-color.png?branch=master)](http://travis-ci.org/iimog/wikidata-game-flower-color)
[![Coverage Status](https://coveralls.io/repos/github/iimog/wikidata-game-flower-color/badge.svg?branch=master)](https://coveralls.io/github/iimog/wikidata-game-flower-color?branch=master)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![DOI](https://zenodo.org/badge/DOI/10.5281/zenodo.260330.svg)](https://doi.org/10.5281/zenodo.260330)


A mini-game for the [distributed wikidata game](https://tools.wmflabs.org/wikidata-game/distributed/).
It helps to assign the flower color property [P2827](https://www.wikidata.org/wiki/Property:P2827) to plants in [wikidata](https://www.wikidata.org/wiki/Wikidata:Main_Page).
Attention! The Wikidata API action is disabled, so no values are altered there (see Limitations section for details).

### Idea
Many Wikidata items contain images with clearly visible flowers.
Also a lot of Wikipedia articles contain textual descriptions of plant morphology including flower color.
This information can be easily extracted by a human but not automatically by a computer.
Therefore this game aims to make it easy for people to add this information to the structured database of Wikidata.

### Limitations
Guessing flower color from images can be problematic for a number of reasons.
Perception of colors by humans is different and the game only provides a limited list of predefined color categories (e.g. blue).
Fine distinctions like light-blue vs dark-blue are not possible.
Using the game it is only possible to add a single color and not to cover cases where one flower has multiple colors,
 different parts of the flower (e.g. sepals and petals) have different colors or one species appears in different forms.
Further concerns about the reliability of entries generated with this game without citing concrete sources exist.
Therefore the API action is disabled for now.
Here is the relevant part of the conversation on [Wikidata](https://www.wikidata.org/wiki/Property_talk:P2827):

> Iimog, without good sources these additions are worthless. I reverted two of your addition allready. --Succu (talk) 09:45, 27 January 2017 (UTC)
> Looks like your source is TraitBank (flower color) which in turn is heavily based on USDAs PLANTS Characteristics. There flower color is defined as the „predominant color of the flowers“. --Succu (talk) 17:14, 28 January 2017 (UTC)
>> Succu, thanks for the feedback. However, I don't agree that those additions are worthless without good sources. My assumption was that the flower color of many plants can be easily derived by anyone from existing images in wikidata and wikipedia articles. Many articles contain sections describing the plant including the color of the flower for example Monarda didyma contains "It has ragged, bright red tubular flowers 3–4 cm long, [...]". I think it would be very beneficial to have this information available in a structured, machine readable way. Therefore I built the wikidata game to make it easy for everybody to add those values. In fact I used the colors used in TraitBank as a preliminary list of possible colors in the game. As said this game has still some limitations. Nevertheless I consider the additions made by playing the game as valuable. As those additions do not have a source associated it is still easily possible to filter those entries out in a SPARQL query. Please let me know if you do not agree. --Iimog 09:55, 01 February 2017 (UTC)
>>> Iimog, major Wikipedias criticize Wikidata for the lack of reliable sources and I agree. Guessing flower color (P2827) from pictures of uncertain quality (and origin) is banned by Wikipedia:No original research (Q4656524). --Succu (talk) 20:07, 3 February 2017 (UTC)
>>>> Succu, thanks for the further explanation. While I still think that more data of mixed quality can be beneficial I understand that in many cases less data of high quality is preferable. Therefore I deactivated the API action in the flower color game so that no more entries are added. Thanks again for your valuable feedback. Iimog (talk) 09:35, 6 February 2017 (UTC)




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
If you have [wd](https://github.com/maxlath/wikidata-cli) and [jq](https://stedolan.github.io/jq/) installed you can re-generate the [plants.tsv](data/plants.tsv) with the following command:
```bash
wd sparql get_plants.rq | jq -r '.[] | .item + "\t" + .sciname + "\t" + .ncbi' >plants.tsv
```

#### Generate SQL files
This step is only required if you updated the plants or colors tsv files.
In order to convert the tsv files into sql files ready for import into the database you can use this perl commands:
```bash
# Colors
perl -F"\t" -ane '
BEGIN{
    print "INSERT INTO color (wikidata_id, color) VALUES\n";
    $needComma=0;
}
next if(/^#/);
print ",\n" if($needComma);
chomp $F[1];
$needComma=1;
print " ('\''$F[0]'\'', '\''$F[1]'\'')";
END{
    print ";\n";
}' data/colors.tsv >data/colors.sql
# Plants
perl -F"\t" -ane '
BEGIN{
    print "INSERT INTO plant (wikidata_id, scientific_name, finished) VALUES\n";
    $needComma=0;
}
print ",\n" if($needComma);
$needComma=1;
print " ('\''$F[0]'\'', '\''$F[1]'\'', FALSE)";
END{
    print ";\n";
}' data/plants.tsv >data/plants.sql
```

### Hosting
#### Docker
To host an instance of this wikidata game you can use docker.
To get a working setup execute the following commands:
```bash
docker pull greatfireball/generic_postgresql_db
docker pull iimog/wikidata-game-flower-color
docker run -d -e "DB_USER=wikidata" -e "DB_PW=wikidata" -e "DB_NAME=wikidata" --name wikidata-db greatfireball/generic_postgresql_db
docker run -d -p 8083:80 --link wikidata-db:db --name wikidata-web iimog/wikidata-game-flower-color
# now fill the database
docker exec -it wikidata-web /bin/bash
# the following commands are executed inside the docker container
cd /wikidata-game-flower-color
php bin/console doctrine:schema:update --force
# Enter password 'wikidata' when prompted
php bin/console doctrine:database:import data/colors.sql
php bin/console doctrine:database:import data/plants.sql
exit
```
Now the api is reachable at localhost port 8083.
You can check with `http://localhost:8083/api?action=desc`.

### Logo
The [logo](https://cdn.pixabay.com/photo/2016/01/21/19/57/marguerite-1154604_960_720.jpg)
is a [free image](https://pixabay.com/en/service/terms/#usage)
([CC-0](https://creativecommons.org/publicdomain/zero/1.0/deed.en))
from [pixaby.com](https://pixabay.com)
uploaded by user [Dieter_G](https://pixabay.com/en/users/Dieter_G-359839/).

### Changes
#### v0.1.2 <small>(2017-02-06)</small>
 - Deactivate Wikidata API action (decisions are only stored locally but not sent to Wikidata)
#### v0.1.1 <small>(2017-01-27)</small>
 - Add unit tests
 - Add functional tests
 - Add CI via Travis
 - Add coverage reporting via coveralls
#### v0.1.0 <small>(2017-01-26)</small>
 - Initial Release
