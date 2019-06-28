<?php
$carsNames = json_decode(file_get_contents(__DIR__ . '/cars-names.json'), true);

function progressBar($done, $total) {
    $perc = floor(($done / $total) * 100);
    $left = 100 - $perc;
    $write = sprintf("\033[0G\033[2K[%'={$perc}s>%-{$left}s] - $perc%% - $done/$total", "", "");
    fwrite(STDERR, $write);
}

$attributesRanges = [1 => [1, 50], [1, 25], [1, 70], [1, 10]];

$pdo = new PDO('mysql:host=localhost;dbname=DBNAME', 'USER', 'PASSWORD');

// creating the database structure
$start = microtime(true); 
$pdo->exec(file_get_contents(__DIR__ . '/init.sql'));
$end = microtime(true);
echo 'Execution time of creating database structure = ' . ($end - $start) . ' sec.' . PHP_EOL; 

// seeding random data
$nofRecords = 200000;
$start = microtime(true);
$inserts = [];
while ($nofRecords--) {
    $insert = '(NULL, "'
        . $carsNames[array_rand($carsNames)] . ' ' . rand(1, 3) . '",'
        . rand($attributesRanges[1][0], $attributesRanges[1][1]) . ',' // attribute_1
        . rand($attributesRanges[2][0], $attributesRanges[2][1]) . ',' // attribute_2
        . rand($attributesRanges[3][0], $attributesRanges[3][1]) . ',' // attribute_3
        . rand($attributesRanges[4][0], $attributesRanges[4][1]) . ')'; // attribute_4
    $inserts[] = $insert;
    if ($nofRecords % 1000 === 0) {
        $pdo->exec('INSERT INTO raw_data VALUES ' . implode(', ', $inserts));
        $inserts = [];
    }
}
$end = microtime(true);
echo 'Execution time of seeding random data = ' . ($end - $start) . ' sec.' . PHP_EOL;

// creating cache data
$start = microtime(true);
$attribute_1_cache = [];
$attribute_2_cache = [];
$attribute_3_cache = [];
$attribute_4_cache = [];
$query = $pdo->prepare('SELECT * FROM raw_data');
$query->execute();
while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
    $attribute_1_cache[] = '(' . $data['attribute_2'] . ', ' .$data['attribute_3'] . ', ' .$data['attribute_4'] . ', ' .$data['attribute_1'] . ')';
    $attribute_2_cache[] = '(' . $data['attribute_1'] . ', ' .$data['attribute_3'] . ', ' .$data['attribute_4'] . ', ' .$data['attribute_2'] . ')';
    $attribute_3_cache[] = '(' . $data['attribute_1'] . ', ' .$data['attribute_2'] . ', ' .$data['attribute_4'] . ', ' .$data['attribute_3'] . ')';
    $attribute_4_cache[] = '(' . $data['attribute_1'] . ', ' .$data['attribute_2'] . ', ' .$data['attribute_3'] . ', ' .$data['attribute_4'] . ')';
}
$attribute_1_cache_unique = array_chunk(array_unique($attribute_1_cache), 1000);
$attribute_2_cache_unique = array_chunk(array_unique($attribute_2_cache), 1000);
$attribute_3_cache_unique = array_chunk(array_unique($attribute_3_cache), 1000);
$attribute_4_cache_unique = array_chunk(array_unique($attribute_4_cache), 1000);
foreach ($attribute_1_cache_unique as $inserts) {
    $pdo->exec('INSERT INTO attribute_1_cache VALUES ' . implode(', ', $inserts));
}
echo '...attribute 1 has been cached' . PHP_EOL;
foreach ($attribute_2_cache_unique as $inserts) {
    $pdo->exec('INSERT INTO attribute_2_cache VALUES ' . implode(', ', $inserts));
}
echo '...attribute 2 has been cached' . PHP_EOL;
foreach ($attribute_3_cache_unique as $inserts) {
    $pdo->exec('INSERT INTO attribute_3_cache VALUES ' . implode(', ', $inserts));
}
echo '...attribute 3 has been cached' . PHP_EOL;
foreach ($attribute_4_cache_unique as $inserts) {
    $pdo->exec('INSERT INTO attribute_4_cache VALUES ' . implode(', ', $inserts));
}
echo '...attribute 4 has been cached' . PHP_EOL;
$end = microtime(true);
echo 'Execution time of creating cache = ' . ($end - $start) . ' sec.' . PHP_EOL;

// testing time for raw data
$times = [];
$attributes = [1, 2, 3, 4];
$nofRepetition = 250;
$repetition = 0;
while ($repetition++ < $nofRepetition) {
    foreach ($attributes as $attribute) {
        $filterAttributes = array_filter($attributes, function ($filterAttribute) use ($attribute) {
            return $filterAttribute !== $attribute;
        });
        shuffle($filterAttributes);
        $filterAttributes = array_slice($filterAttributes, 0, rand(1, 3));
        $start = microtime(true);
        $sql = 'SELECT DISTINCT attribute_' . $attribute . ' FROM raw_data WHERE ' . implode(' AND ', array_map(function ($attribute) use (&$attributesRanges) {
            return 'attribute_' . $attribute . ' = ' . rand($attributesRanges[$attribute][0], $attributesRanges[$attribute][1]);
        }, $filterAttributes));
        $query = $pdo->query($sql);
        $results = $query->fetchAll();
        $end = microtime(true);
        $times[] = $end - $start;
    }
    progressBar($repetition, $nofRepetition);
}
echo PHP_EOL . 'Average selection query time for raw data table = ' . (array_sum($times) / count($times)) . ' sec.' . PHP_EOL;

// testing time for indexed caches
$times = [];
$nofRepetition = 250;
$repetition = 0;
while ($repetition++ < $nofRepetition) {
    foreach ($attributes as $attribute) {
        $filterAttributes = array_filter($attributes, function ($filterAttribute) use ($attribute) {
            return $filterAttribute !== $attribute;
        });
        shuffle($filterAttributes);
        $filterAttributes = array_slice($filterAttributes, 0, rand(1, 3));
        $start = microtime(true);
        $sql = 'SELECT attribute_' . $attribute . '_value FROM attribute_' . $attribute . '_cache WHERE ' . implode(' AND ', array_map(function ($attribute) use (&$attributesRanges) {
            return 'attribute_' . $attribute . '_filter = ' . rand($attributesRanges[$attribute][0], $attributesRanges[$attribute][1]);
        }, $filterAttributes));
        $query = $pdo->query($sql);
        $results = $query->fetchAll();
        $end = microtime(true);
        $times[] = $end - $start;
    }
    progressBar($repetition, $nofRepetition);
}
echo PHP_EOL . 'Average selection query time for indexed caches = ' . (array_sum($times) / count($times)) . ' sec.' . PHP_EOL;

