<?php

namespace NextrasTests\Orm;

use DateTime;
use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany as MHM;


/**
 * @property int                $id {primary}
 * @property string             $title
 * @property Author             $author {m:1 Author::$books}
 * @property Author|NULL        $translator {m:1 Author::$translatedBooks}
 * @property MHM|Tag[]          $tags {m:m Tag::$books, isMain=true}
 * @property Book|NULL          $nextPart {1:1 Book::$previousPart, isMain=true}
 * @property Book|NULL          $previousPart {1:1 Book::$nextPart}
 * @property Ean|NULL           $ean {1:1 Ean::$book, isMain=true, cascade=[persist, remove]}
 * @property Publisher          $publisher {m:1 Publisher::$books}
 * @property DateTimeImmutable  $publishedAt {default now}
 * @property NULL|DateTime      $printedAt
 */
final class Book extends Entity
{
}
