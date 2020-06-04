<?php
namespace backend\tests\acceptance;

use backend\tests\AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function checkHome(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('管理中心');

        // $I->seeLink('About');
        // $I->click('About');
        // $I->wait(2); // wait for page to be opened

        // $I->see('This is the login page.');
    }
}
