# Podcast RSS Reader

Component for easy handling and management of rss feed for podcasts.

# How to use

## Requirements

- PHP >= 7.4.
- Composer.

## Instalation

```bash
composer require diego-brocanelli/podcast-rss-reader dev-master
```

# How to contribute

Open an issue exposing your point to be analyzed, including detailing the point.

To contribute to the project, create a fork and send your pull request.

# Tests

```bash
composer tests
```

## Code Analysis

The command below will run PHPStan level 4 analysis.

```bash
composer code-analysis
```

# Example

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use DiegoBrocanelli\Podcast\Podcast;
use DiegoBrocanelli\Podcast\Reader;

$feed = 'https://devnaestrada.com.br/feed.xml';

$podcast = new Podcast( new Reader($feed) );

$podcast->getEpisodes(); //Return: array<Episodes>
```

# Methods

## info(): array

Responsible for returning the base data of the rss feed, with the exception of episodes.

| Attribute  | Type |
|---|---|
| title | string |
| link | string |
| description | string |
| lastBuildDate | DateTime |
| pubDate | DateTime |
| language | string |

## getImageInfo()

Responsible for returning the `DiegoBrocanelli\Podcast\Image` object with its attributes.

| Attribute  | Type |
|---|---|
| title | string |
| url | string |
| link | string |

## getEpisodes()

Responsible for returning a list of `DiegoBrocanelli\Podcast\Episode` objects with their attributes.

| Attribute  | Type |
|---|---|
| title | string |
| link | string |
| pubDate | DateTime |
| guid | string |
| comments | string |
| category | string |
| description | string |
| audio | string |

## lastBuildDate()

Responsible for returning the date for the last episode released, returning a `DateTime object`.

## biggerThen(DateTime $date): array

Allows you to set a date to search for episodes. Bringing all records located from the date informed.

# Author

[Diego Brocanelli Francisco](http://www.diegobrocanelli.com.br/)

# License

[MIT](https://github.com/Diego-Brocanelli/podcast-rss-reader/blob/main/LICENSE)