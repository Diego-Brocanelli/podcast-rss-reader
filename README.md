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

The command below will run PHPStan level 8 analysis.

```bash
composer analyse
```

## PHP Code Sniffer

The command below will run PHPStan level 8 analysis.

```bash
composer phpcs
```

## Test, Code Analysis and PHP Code Sniffer

```bash
composer all
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

| Methods  | Return |
|---|---|
| getTitle() | string |
| getUrl() | string |
| getLink() | string |

## getEpisodes()

Responsible for returning a list of `DiegoBrocanelli\Podcast\Episode` objects with their attributes.

| Methods  | Return |
|---|---|
| getTitle() | string |
| getLink() | string |
| getPubDate() | DateTime |
| getGuid() | string |
| getComments() | string |
| getCategory() | string |
| getDescription() | string |
| getAudio() | string |

## lastBuildDate()

Responsible for returning the date for the last episode released, returning a `DateTime object`.

## biggerThen(DateTime $date): array

Allows you to set a date to search for episodes. Bringing all records located from the date informed.

# Author

<a href="https://www.diegobrocanelli.com.br/">
<img src="https://avatars2.githubusercontent.com/u/4108889?s=460&v=4" width="150px">
</a>

# License

[MIT](https://github.com/Diego-Brocanelli/podcast-rss-reader/blob/main/LICENSE)
