<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://nid.naver.com/nidlogin.login?url=http%3A%2F%2Fbookingapi.naver.com%2Fauth%2Fcallback&user=0&locale=ko_KR')
                ->type('id', 'smartbos')
                ->type('pw', '100dlfrhdrhd()')
                ->click('input.btn_global')
                ->waitForText('내 서비스');
        });
    }
}
