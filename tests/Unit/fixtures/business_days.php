<?php

use AugustoMoura\LaravelToolkit\Helpers\BusinessDayCalculator as Calculator;

return [
    [
        'Iterate 1 day',
        new Carbon('2013-01-01'),
        1,
        new Carbon('2013-01-02'),
    ],
    [
        'Iterate 2 days',
        new Carbon('2014-07-25'),
        2,
        new Carbon('2014-07-27'),
    ],
    [
        'Iterate 3 days',
        new Carbon('2014-06-29'),
        3,
        new Carbon('2014-07-02'),
    ],
    [
        'Iterate 1 year',
        new Carbon('2014-06-29'),
        365,
        new Carbon('2015-06-29'),
    ],
    [
        'Iterate 1 day with free saturday and sunday',
        new Carbon('2014-07-25'),
        1,
        new Carbon('2014-07-28'),
        [Calculator::SATURDAY, Calculator::SUNDAY],
    ],
    [
        'Iterate 1 day with free friday',
        new Carbon('2014-07-31'),
        1,
        new Carbon('2014-08-02'),
        [Calculator::FRIDAY],
    ],
    [
        'Iterate 1 day with free all days except monday',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-07'),
        [
            Calculator::TUESDAY,
            Calculator::WEDNESDAY,
            Calculator::THURSDAY,
            Calculator::FRIDAY,
            Calculator::SATURDAY,
            Calculator::SUNDAY,
        ],
    ],
    [
        'Iterate 1 day with free day in the middle',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-03'),
        [],
        [new Carbon('2014-07-02')]
    ],
    [
        'Iterate 2 days with 3 free days in the middle',
        new Carbon('2014-07-01'),
        2,
        new Carbon('2014-07-06'),
        [],
        [new Carbon('2014-07-02'), new Carbon('2014-07-03'), new Carbon('2014-07-04')]
    ],
    [
        'Iterate 1 day with free Thursday and 2 free days in the middle',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-05'),
        [Calculator::THURSDAY],
        [new Carbon('2014-07-02'), new Carbon('2014-07-04')]
    ],
    [
        'Iterate 4 days with free Tuesday and 2 free days in the middle',
        new Carbon('2014-06-30'),
        4,
        new Carbon('2014-07-07'),
        [Calculator::TUESDAY],
        [new Carbon('2014-07-02'), new Carbon('2014-07-04')]
    ],
    [
        'Iterate 1 day with holiday in the middle',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-03'),
        [],
        [],
        [new Carbon('2000-07-02')]
    ],
    [
        'Iterate 1 day with holiday and free day in the middle',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-04'),
        [],
        [new Carbon('2014-07-03')],
        [new Carbon('2000-07-02')]
    ],
    [
        'Iterate 1 day with holiday, free day and free Friday in the middle',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-05'),
        [Calculator::FRIDAY],
        [new Carbon('2014-07-03')],
        [new Carbon('2000-07-02')]
    ],
    [
        'Iterate 1 day with holiday, free day and free Friday in the middle + other unrelated days',
        new Carbon('2014-07-01'),
        1,
        new Carbon('2014-07-05'),
        [Calculator::SUNDAY, Calculator::FRIDAY],
        [new Carbon('2014-08-03'), new Carbon('2014-10-03'), new Carbon('2014-07-03')],
        [new Carbon('2000-07-22'), new Carbon('2000-09-02'), new Carbon('2000-07-02')]
    ],
];