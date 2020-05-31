<?php declare(strict_types = 1);

namespace Manticoresearch\Test;

use Manticoresearch\Client;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Query\BoolQuery;
use Manticoresearch\Query\Distance;
use Manticoresearch\Query\Equals;
use Manticoresearch\Query\Match;
use Manticoresearch\Query\Range;
use Manticoresearch\ResultSet;
use Manticoresearch\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{

    /** @var \Manticoresearch\Search */
    private static $search;

    public function testConstructor(): void
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
        ];
        $client = new Client($params);
        $searchObj = new Search($client);
        $this->assertEquals($client, $searchObj->getClient());
    }

    public function testFilterLTE(): void
    {
        $results = self::$search->filter('year', 'lte', 1990)->get();
        $this->assertEquals([1979, 1986], $this->yearsFromResults($results));
    }

    public function testFilterLTEAsObject(): void
    {
        $results = self::$search->filter(new Range('year', ['lte' => 1990]))->get();
        $this->assertEquals([1979, 1986], $this->yearsFromResults($results));
    }

    public function testFilterGTE(): void
    {
        $results = self::$search->filter('year', 'gte', 1990)->get();
        $this->assertEquals([2014, 2010, 2018, 1992], $this->yearsFromResults($results));
    }

    public function testFilterEq(): void
    {

        $results = self::$search->filter('year', 'equals', 1979)->get();
        $this->assertCount(1, $results);
    }

    public function testFilterRange(): void
    {
        $results = self::$search->filter('year', 'range', [1960, 1992])->get();
        $this->assertEquals([1979, 1986, 1992], $this->yearsFromResults($results));
    }

    /**
     * Demonstrate that the array of years gets smaller for the same phrase match as the limit is applied
     */
    public function testLimitMethod(): void
    {
        $results = self::$search->limit(3)->phrase('team of explorers')->get();
        $this->assertEquals([1986, 2014, 1992], $this->yearsFromResults($results));

        $results = self::$search->limit(2)->phrase('team of explorers')->get();
        $this->assertEquals([1986, 2014], $this->yearsFromResults($results));

        $results = self::$search->limit(1)->phrase('team of explorers')->get();
        $this->assertEquals([1986], $this->yearsFromResults($results));
    }

    /**
     * Demonstrate that the array of years gets smaller for the same phrase match as the limit is applied
     */
    public function testMaxMatchesMethod(): void
    {
        $results = self::$search->maxMatches(3)->phrase('team of explorers')->get();
        $this->assertEquals([1986, 2014, 1992], $this->yearsFromResults($results));

        $results = self::$search->maxMatches(2)->phrase('team of explorers')->get();
        $this->assertEquals([1986, 2014], $this->yearsFromResults($results));

        $results = self::$search->maxMatches(1)->phrase('team of explorers')->get();
        $this->assertEquals([1986], $this->yearsFromResults($results));
    }

    public function testNotFilterLTE(): void
    {
        $results = self::$search->phrase('team of explorers')->notFilter('year', 'lte', 1990)->get();
        $this->assertEquals([2014, 1992], $this->yearsFromResults($results));

        $results = self::$search->phrase('team of explorers')->notFilter('year', 'lte', 1992)->get();
        $this->assertEquals([2014], $this->yearsFromResults($results));
    }

    public function testNotFilterRange(): void
    {
        $results = self::$search->notFilter('year', 'range', [1900, 1990])->get();
        $this->assertEquals([2014, 2010, 2018, 1992], $this->yearsFromResults($results));
    }

    public function testNotFilterRangeAsObject(): void
    {
        $range = new Range('year', ['gte' => 1900, 'lte' => 1990]);
        $results = self::$search->notFilter($range)->get();
        $this->assertEquals([2014, 2010, 2018, 1992], $this->yearsFromResults($results));
    }

    public function testOrFilterRange(): void
    {
        $results = self::$search->phrase('team of explorers')->orFilter('year', 'range', [1900, 1990])->get();
        $this->assertEquals([1986], $this->yearsFromResults($results));
    }

    public function testOrFilterRangeAsObject(): void
    {
        $range = new Range('year', ['gte' => 1900, 'lte' => 1990]);

        $results = self::$search->phrase('team of explorers')->orFilter($range)->get();
        $this->assertEquals([1986], $this->yearsFromResults($results));
    }

    /**
     * Search for years less than 1990, more than 1999
     */
    public function testOrFilterRangeSkip90s(): void
    {
        $results = self::$search->
            orFilter('year', 'lt', 1990)->
            orFilter('year', 'gte', 2000)->
            get();
        $this->assertEquals([2014, 2010, 2018, 1979, 1986], $this->yearsFromResults($results));
    }

    public function testOrFilterEquals(): void
    {
        $results = self::$search->
        orFilter('year', 'equals', 1979)->
        orFilter('year', 'equals', 1986)->
        get();
        $this->assertEquals([1979, 1986], $this->yearsFromResults($results));
    }

    public function testSortMethodAscending(): void
    {
        $results = self::$search->sort('year')->phrase('team of explorers')->get();
        $this->assertEquals([1986, 1992, 2014], $this->yearsFromResults($results));
    }

    public function testSortMethodDescending(): void
    {
        $results = self::$search->sort('year', 'desc')->phrase('team of explorers')->get();
        $this->assertEquals([2014, 1992, 1986], $this->yearsFromResults($results));
    }

    public function testOffsetMethod(): void
    {
        $results = self::$search->offset(0)->phrase('team of explorers')->get();
        $this->assertEquals([1986, 2014, 1992], $this->yearsFromResults($results));

        $results = self::$search->offset(1)->phrase('team of explorers')->get();
        $this->assertEquals([2014, 1992], $this->yearsFromResults($results));

        $results = self::$search->offset(2)->phrase('team of explorers')->get();
        $this->assertEquals([1992], $this->yearsFromResults($results));
    }

    public function testPhraseMethodAllFieldsMatchingPhrase(): void
    {
        $results = self::$search->phrase('team of explorers')->get();
        $this->assertCount(3, $results);
    }

    public function testPhraseMethodAllFieldsNoMatchingPhrase(): void
    {
        // search for a non matching phrase
        $results = self::$search->phrase('team with explorers')->get();
        $this->assertCount(0, $results);
    }

    public function testPhraseMethodSpecifiedFieldsTitleOnly(): void
    {
        // the title fields do not contain the matching text
        $results = self::$search->phrase('team of explorers', 'title')->get();
        $this->assertCount(0, $results);
    }

    public function testPhraseMethodSpecifiedFieldsPlotOnly(): void
    {
        $results = self::$search->phrase('team of explorers', 'plot')->get();
        $this->assertCount(3, $results);
    }

    public function testPhraseMethodSpecifiedFieldsTitleAndPlot(): void
    {
        $results = self::$search->phrase('team of explorers', 'title,plot')->get();
        $this->assertCount(3, $results);
    }

    public function testMatchExactPhrase(): void
    {
        $q = new BoolQuery();
        $q->must(new \Manticoresearch\Query\MatchPhrase('wormhole in space', 'title,plot'));
        $result = self::$search->search($q)->get();
        $this->assertCount(1, $result);

        $q->must(new \Manticoresearch\Query\MatchPhrase('WORMhoLE in space', 'title,plot'));
        $result = self::$search->search($q)->get();
        $this->assertCount(1, $result);
    }

    public function testMatchInexactPhrase(): void
    {
        $q = new BoolQuery();
        $q->must(new \Manticoresearch\Query\MatchPhrase('wormhole space', 'title,plot'));
        $result = self::$search->search($q)->get();
        $this->assertCount(0, $result);
    }

    public function testSearchDistanceMethod(): void
    {
        $result = self::$search->distance([
            'location_anchor'=>
                ['lat'=>52.2, 'lon'=> 48.6],
            'location_source' =>
                ['lat', 'lon'],
            'location_distance' => '100 km',
        ])->get();

        $this->assertCount(4, $result);
    }

    public function testDistanceObjectArrayParamCreation(): void
    {
        $q = new BoolQuery();

        $q->must(new \Manticoresearch\Query\Distance([
            'location_anchor'=>
                ['lat'=>52.2, 'lon'=> 48.6],
            'location_source' =>
                ['lat', 'lon'],
            'location_distance' => '100 km',
        ]));

        $result = self::$search->search($q)->get();
        $this->assertCount(4, $result);
    }

    public function testDistanceArrayParamCreation(): void
    {
        $q = new BoolQuery();

        $q->must(new \Manticoresearch\Query\Distance([
            'location_anchor'=>
                ['lat'=>52.2, 'lon'=> 48.6],
            'location_source' =>
                ['lat', 'lon'],
            'location_distance' => '100 km',
        ]));

        $result = self::$search->search($q)->get();
        $this->assertCount(4, $result);
    }

    public function testDistanceArrayParamCreationNoLocationAnchor(): void
    {
        $q = new BoolQuery();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('source attributes not provided');
        $q->must(new \Manticoresearch\Query\Distance([
            'location_anchor'=>
                ['lat'=>52.2, 'lon'=> 48.6],
            'location_distance' => '100 km',
        ]));
    }

    public function testDistanceArrayParamCreationNoLocationDistancce(): void
    {
        $q = new BoolQuery();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('distance not provided');
        $q->must(new \Manticoresearch\Query\Distance([
            'location_anchor'=>
                ['lat'=>52.2, 'lon'=> 48.6],
            'location_source' =>
                ['lat', 'lon'],
        ]));
    }

    public function testDistanceArrayParamCreationNoLocationSource(): void
    {
        $q = new BoolQuery();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('anchors not provided');
        $q->must(new \Manticoresearch\Query\Distance([
            'location_source' =>
                ['lat', 'lon'],
            'location_distance' => '100 km',
        ]));
    }

    public function testDistanceUsingObject(): void
    {
        $q = new BoolQuery();
        $distanceQuery = new Distance();
        $distanceQuery->setAnchor(52.2, 48.6);
        $distanceQuery->setSource(['lat', 'lon']);
        $distanceQuery->setDistance('100 km');
        // the default
        $distanceQuery->setDistanceType('adaptive');
        $q->must($distanceQuery);

        $result = self::$search->search($q)->get();

        $this->assertCount(4, $result);
    }

    public function testTextSearch(): void
    {
        $result = self::$search->search('"team of explorers"/2')->get();
        $this->assertCount(4, $result);
    }

    public function testTextSearchFilterToAYear(): void
    {
        $result = self::$search->search('"team of explorers"/2')->filter('year', 'equals', 2014)->get();
        $this->assertCount(1, $result);
    }

    public function testMatchAllFieldsOrMatch(): void
    {
        $result = self::$search->match('team of explorers')->get();
        $this->assertCount(5, $result);
    }

    public function testMatchTitleOnly(): void
    {
        $result = self::$search->match(['query' => 'team of explorers', 'operator' => 'and'], 'title')->get();
        $this->assertCount(0, $result);
    }

    public function testMatchTitleAndPlot(): void
    {
        $result = self::$search->match(['query' => 'team of explorers', 'operator' => 'and'], 'title,plot')->get();
        $this->assertCount(3, $result);
    }

    public function testMatchAllFieldsAnd(): void
    {
        $result = self::$search->match(['query' => 'team of explorers', 'operator' => 'and'])->get();
        $this->assertCount(3, $result);
    }

    public function testMatchFilteredToSingleYear(): void
    {
        $result = self::$search->match(['query' => 'team of explorers', 'operator' => 'and'])->
            filter('year', 'equals', 2014)->get();
        $this->assertCount(1, $result);
    }

    public function testComplexSearchWithFilters(): void
    {
        $result = self::$search->search('"team of explorers"/2')
            ->expression('genre', "in(meta['genre'],'adventure')")
            ->notfilter('genre', 'equals', 1)
            ->filter('year', 'lte', 2000)
            ->filter("advise", 'equals', 'R')
            ->get();

        $this->assertCount(2, $result);
    }

    public function testMatchBoolQueryMust(): void
    {
        $q = new BoolQuery();
        $q->must(new Match(['query' => 'team of explorers', 'operator' => 'and'], '*'));
        $result = self::$search->search($q)->get();
        $this->assertCount(3, $result);
    }

    public function testMatchBoolQueryShould(): void
    {
        $q = new BoolQuery();
        $q->should(new Match(['query' => 'team of explorers', 'operator' => 'and'], '*'));
        $result = self::$search->search($q)->get();
        $this->assertCount(3, $result);
    }

    public function testBoolQueryMutipleFilters1(): void
    {
        $q = new BoolQuery();
        $q->must(new Match(['query' => 'team of explorers', 'operator' => 'or'], '*'));
        $q->must(new Equals('year', 2014));
        $result = self::$search->search($q)->get();
        $this->assertCount(1, $result);
    }

    public function testBoolQueryMutipleFilters2(): void
    {
        $q = new BoolQuery();
        $q->must(new Match(['query' => 'team of explorers', 'operator' => 'or'], '*'));
        $q->must(new Range('year', ['lte' => 2020]));
        $result = self::$search->search($q)->get();
        $this->assertCount(5, $result);
    }

    public function testResultSetNextRewind(): void
    {
        $result = $this->getResultSet();
        $this->assertEquals(0, $result->key());

        $result->next();
        $this->assertEquals(1, $result->key());
        $result->next();
        $this->assertEquals(2, $result->key());
        $result->rewind();
        $this->assertEquals(0, $result->key());
    }

    public function testResultSetGetTotal(): void
    {
        $result = $this->getResultSet();
        $this->assertEquals(4, $result->getTotal());
    }

    public function testResultSetGetTime(): void
    {
        $result = $this->getResultSet();
        $this->assertGreaterThanOrEqual(0, $result->getTime());
    }

    public function testResultSetHasNotTimedOut(): void
    {
        $result = $this->getResultSet();
        $this->assertFalse($result->hasTimedout());
    }

    public function testResultSetGetResponse(): void
    {
        $result = $this->getResultSet();
        $keys = \array_keys($result->getResponse()->getResponse());
        \sort($keys);
        $this->assertEquals(['hits', 'timed_out', 'took'], $keys);
    }

    public function testResultSetGetNullProfile(): void
    {
        $result = $this->getResultSet();
        $this->assertNull($result->getProfile());
    }

    /** @todo What is the intended functionality here? */
    public function testNonExistentSource(): void
    {
        $results = self::$search->setSource('source_does_not_exist')->phrase('team of explorers')->get();

        while ($results->valid()) {
            $hit = $results->current();
            $this->assertEquals([], $hit->getData());
            $results->next();
        }
    }

    public function testProfileForSearch(): void
    {
        $results = self::$search->profile()->phrase('team of explorers')->get();
        $profile = $results->getProfile();
        $expected = 'PHRASE( AND(KEYWORD(team, querypos=1)),  AND(KEYWORD(of, querypos=2)),  AND(KEYWORD(explorers, ' .
            'querypos=3)))';
        $this->assertEquals($expected, $profile['query']['description']);
    }

    public function testResultHitGetScore(): void
    {
        $resultHit = $this->getFirstResultHit();
        $this->assertEquals(3468, $resultHit->getScore());
    }

    public function testResultHitGetID(): void
    {
        $resultHit = $this->getFirstResultHit();
        $this->assertEquals(6, $resultHit->getId());
    }

    public function testResultHitGetValue(): void
    {
        $resultHit = $this->getFirstResultHit();
        $this->assertEquals(1986, $resultHit->get('year'));
        $this->assertEquals(1986, $resultHit->__get('year'));
    }

    public function testResultHitHasValue(): void
    {
        $resultHit = $this->getFirstResultHit();
        $this->assertTrue($resultHit->has('year'));
        $this->assertTrue($resultHit->__isset('year'));
    }

    public function testResultHitDoesNotHaveValue(): void
    {
        $resultHit = $this->getFirstResultHit();
        $this->assertFalse($resultHit->has('nonExistentKey'));
        $this->assertFalse($resultHit->__isset('nonExistentKey'));
        $this->assertEquals([], $resultHit->get('nonExistentKey'));
    }

    public function testGetHighlight(): void
    {
        $results = self::$search->match('salvage')->highlight(
            ['plot'],
            ['pre_tags' => '<i>', 'post_tags'=>'</i>'],
        )->get();

        $this->assertEquals(1, $results->count());
        $this->assertEquals(
            ['plot' => [' is rescued by a deep <i>salvage</i> team of explorers after being']],
            $results->current()->getHighlight(),
        );
    }

    public function testHighlightParamsMissing(): void
    {
        $results = self::$search->match('salvage')->highlight()->get();

        $this->assertEquals(1, $results->count());

        // default highlighter is bold, all text fields are searched.  The 'plot field' has a highlights match
        $this->assertCount(2, $results->current()->getHighlight());
    }

    public function testResultHitGetData(): void
    {
        $resultHit = $this->getFirstResultHit();
        $keys = \array_keys($resultHit->getData());
        \sort($keys);
        $this->assertEquals([
            0 => 'advise',
            1 => 'language',
            2 => 'lat',
            3 => 'lon',
            4 => 'meta',
            5 => 'plot',
            6 => 'rating',
            7 => 'title',
            8 => 'year',
        ], $keys);
    }

    public function testSetGetID(): void
    {
        $resultHit = $this->getFirstResultHit();
        $arbitraryID = 668689;
        $resultHit->setId($arbitraryID);
        $this->assertEquals($arbitraryID, $resultHit->getId());
    }

    public function testGetBody(): void
    {

        self::$search->phrase('team of explorers')->get();
        $body = self::$search->getBody();
        $this->assertEquals([
            'index' => 'movies',
            'query' =>
                [
                    'bool' =>
                        [
                            'must' =>
                                [
                                    0 =>
                                        [
                                            'match_phrase' =>
                                                [
                                                    '*' => 'team of explorers',
                                                ],
                                        ],
                                ],
                        ],
                ],
        ], $body);
    }

    public function testGetClient(): void
    {
        $client = self::$search->getClient();
        $this->assertInstanceOf('Manticoresearch\Client', $client);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$search = self::indexDocuments();
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::$search->reset();
        self::$search->setIndex('movies');
    }

    protected function getResultSet(): ResultSet
    {
        return self::$search->search('"team of explorers"/2')->get();
    }

    protected function getFirstResultHit(): \Manticoresearch\ResultHit
    {
        $result = $this->getResultSet();
        $result->rewind();
        $this->assertEquals(0, $result->key());

        return $result->current();
    }

    protected static function indexDocuments(): Search
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
        ];
        $client = new Client($params);
        $client->indices()->drop(['index' => 'movies', 'body'=>['silent'=>true]]);
        $index = [
            'index' => 'movies',
            'body' => [
                'columns' => ['title' => ['type' => 'text'],
                    'plot' => ['type' => 'text'],
                    'year' => ['type' => 'integer'],
                    'rating' => ['type' => 'float'],
                    'language' => ['type' => 'multi'],
                    'meta' => ['type' => 'json'],
                    'lat' => ['type' => 'float'],
                    'lon' => ['type' => 'float'],
                    'advise' => ['type' => 'string'],
                ],
            ],
        ];

        $client->indices()->create($index);
        $docs = [
            ['insert' => ['index' => 'movies', 'id' => 2, 'doc' =>
                ['title' => 'Interstellar',
                    'plot' => 'A team of explorers travel through a wormhole in space in an attempt to ensure'.
                        ' humanity\'s survival.',
                    'year' => 2014, 'rating' => 8.5,
                    'meta' => ['keywords' => ['astronaut', 'relativity', 'nasa'],
                        'genre' => ['drama', 'scifi', 'thriller']],
                    'lat' => 51.2, 'lon' => 47.5,
                    'advise' => 'PG-13',
                ],
            ]],
            ['insert' => ['index' => 'movies', 'id' => 3, 'doc' =>
                ['title' => 'Inception', 'plot' => 'A thief who steals corporate secrets through the use of'.
                    ' dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                    'year' => 2010, 'rating' => 8.8,
                    'meta' => ['keywords' => ['dream', 'thief', 'subconscious'],
                        'genre' => ['action', 'scifi', 'thriller']],
                    'lat' => 51.9, 'lon' => 48.5,
                    'advise' => 'PG-13',
                ],
            ]],
            ['insert' => ['index' => 'movies', 'id' => 4, 'doc' =>
                ['title' => '1917 ', 'plot' => ' As a regiment assembles to wage war deep in enemy territory, two'.
                    ' soldiers are assigned to race against time and deliver a message that will stop 1,600 men from'.
                    ' walking straight into a deadly trap.',
                    'year' => 2018, 'rating' => 8.4,
                    'meta' => ['keywords' => ['death', ' trench'], 'genre' => ['drama', 'war']],
                    'lat' => 51.1, 'lon' => 48.1,
                    'advise' => 'PG-13',
                ],
            ]],
            ['insert' => ['index' => 'movies', 'id' => 5, 'doc' =>
                ['title' => 'Alien', 'plot' => ' After a space merchant vessel receives an unknown transmission as a'.
                    ' distress call, one of the team\'s member is attacked by a mysterious life form and they soon '.
                    'realize that its life cycle has merely begun.',
                    'year' => 1979, 'rating' => 8.4,
                    'meta' => ['keywords' => ['spaceship', 'monster', 'nasa'], 'genre' => ['scifi', 'horror']],
                    'lat' => 52.2, 'lon' => 48.9,
                    'advise' => 'R',
                ],
            ]],
            ['insert' => ['index' => 'movies', 'id' => 6, 'doc' =>
                ['title' => 'Aliens', 'plot' => ' Ellen Ripley is rescued by a deep salvage team of explorers after'.
                    ' being in hypersleep for 57 years. The moon that the Nostromo visited has been colonized by '.
                    'explorers, but contact is lost. This time, colonial marines have impressive firepower, but will'.
                    ' that be enough?',
                    'year' => 1986, 'rating' => 8.3,
                    'meta' => ['keywords' => ['alien', 'monster', 'soldier'],
                        'genre' => ['scifi', 'action', 'adventure']],
                    'lat' => 51.6, 'lon' => 48.0,
                    'advise' => 'R',
                ],
            ]],
            ['insert' => ['index' => 'movies', 'id' => 10, 'doc' =>
                ['title' => 'Alien 3', 'plot' => 'After her last encounter, without a team Ellen Ripley team of '.
                    'explorers crash-lands on Fiorina 161, a maximum security prison. When a series of strange and '.
                    'deadly events occur shortly after her arrival, Ripley realizes that she has brought along an '.
                    'unwelcome visitor.',
                    'year' => 1992, 'rating' => 6.5,
                    'meta' => ['keywords' => ['alien', 'prison', 'android'], 'genre' => ['scifi', 'horror', 'action']],
                    'lat' => 51.8, 'lon' => 48.2,
                    'advise' => 'R',
                ],
            ]],
        ];
        $client->bulk(['body' => $docs]);

        $search = new Search($client);
        $search->setIndex('movies');

        return $search;
    }

    /**
     * Helper method to return just the years from the results. This is used to validate filtering and sorting
     *
     * @return array<int>
     */
    private function yearsFromResults( ResultSet $results): array
    {
        $years = [];

        while ($results->valid()) {
            $hit = $results->current();
            $data = $hit->getData();

            // year is an integer, cast it as such in order that type checking passes
            $years[] = (int)$data['year'];
            $results->next();
        }

        return $years;
    }

}
