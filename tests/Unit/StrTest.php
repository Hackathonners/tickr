<?php

use App\Support\Str;

class StrTest extends TestCase
{
    public function testSearchableString()
    {
        $query1 = 'wõrd';
        $query2 = 'néw string';
        $query3 = 'a stríng';
        $query4 = '   a string   ';
        $query5 = '   a big string   ';
        $query6 = '   ';
        $query7 = '';

        $this->assertEquals('word', Str::searchable($query1));
        $this->assertEquals('word', Str::searchable($query1, true, 10));

        $this->assertEquals('new string', Str::searchable($query2));
        $this->assertEquals('new%string', Str::searchable($query2, true));
        $this->assertEquals('new%string', Str::searchable($query2, true));

        $this->assertEquals('string', Str::searchable($query3));
        $this->assertEquals('a string', Str::searchable($query3, false, 1));
        $this->assertEquals('a%string', Str::searchable($query3, true, 1));

        $this->assertEquals('big string', Str::searchable($query5));

        $this->assertEquals('', Str::searchable($query6));
        $this->assertEquals('', Str::searchable($query7));
    }
}
