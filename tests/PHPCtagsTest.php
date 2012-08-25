<?php
require_once __DIR__ . '/PHPCtagsTestCase.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-10 at 02:00:19.
 */
class PHPCtagsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPCtags
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new PHPCtags();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Data provider
     **/
    public function provider()
    {
        $testcases_files = array();
        $testcases = scandir(__DIR__ . '/testcases');
        foreach($testcases as $testcase)
        {
            if($testcase === '.' || $testcase === '..') {
                continue;
            }
            $testcases_files[] = array($testcase);
        }
        return $testcases_files;
    }

    /**
     * @covers PHPCtags::export
     * @dataProvider provider
     */
    public function testExport($testcase)
    {
        require_once __DIR__ . '/testcases/' . $testcase;
        $testcase_id = strstr($testcase, '.', true);
        $testcase_class = 't_' . $testcase_id;
        $testcase_object = new $testcase_class;

        $testcase_expect = $testcase_object->getExpectResult();

        ob_start();
        $testcase_example = $testcase_object->getExample();
        $testcase_options = $testcase_object->getOptions();
        $this->object->export($testcase_example, $testcase_options);
        $testcase_result = ob_get_contents();
        ob_end_clean();

        $expected_result = __DIR__ . '/' . $testcase_id . '.testcase.expect';
        $acctural_result = __DIR__ . '/' . $testcase_id . '.testcase.result';

        $msg = <<<EOF
Test case '$testcase' has failed.
Expected result has been dumped to '$expected_result'
Acctural result has been dumped to '$acctural_result'
EOF;

        try {
            $this->assertEquals(md5($testcase_expect), md5($testcase_result), $msg);
        } catch (PHPUnit_Framework_ExpectationFailedException $e) {
            file_put_contents($expected_result, $testcase_expect);
            file_put_contents($acctural_result, $testcase_result);
            throw $e;
        }
    }

}
