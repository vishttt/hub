<?php  //[STAMP] c09d8dc94ab0c52bb8d04e5e76255b6f
namespace enterprise\_generated;

// This class was automatically generated by build task
// You should not change it manually as it will be overwritten on next build
// @codingStandardsIgnoreFile

use tests\codeception\_support\CodeHelper;
use tests\codeception\_support\HumHubHelper;

trait UnitTesterActions
{
    /**
     * @return \Codeception\Scenario
     */
    abstract protected function getScenario();

    
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \tests\codeception\_support\CodeHelper::assertContainsError()
     */
    public function assertContainsError($model, $message) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertContainsError', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \tests\codeception\_support\CodeHelper::assertNotContainsError()
     */
    public function assertNotContainsError($model, $message) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotContainsError', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \tests\codeception\_support\HumHubHelper::inviteUserByEmail()
     */
    public function inviteUserByEmail($email) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('inviteUserByEmail', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \tests\codeception\_support\HumHubHelper::initModules()
     */
    public function initModules() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('initModules', func_get_args()));
    }
}
