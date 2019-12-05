<?php

namespace Anax\View;

use PHPUnit\Framework\TestCase;

/**
 * Views.
 */
class ViewTest extends TestCase
{
    /**
     * View renderes a string.
     */
    public function testRenderString()
    {
        $view = new View();
        $exp = "a string";
        $view->set($exp, [], 0, "string");
        
        ob_start();
        $view->render();
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($exp, $res);
    }



    /**
     * Set a view and render its data.
     */
    public function testAddViewCheckRenderData()
    {
        $view = new View();
        $template = function ($data) {
            $str1 = $data["str1"] ?? null;
            $str2 = $data["str2"] ?? null;
            return "$str1, $str2:";
        };
        $view->set($template, ["str1" => "str1"], 0, "callback");
        
        ob_start();
        $view->render();
        $res = ob_get_contents();
        ob_end_clean();
        $exp = "str1, :";
        $this->assertEquals($exp, $res);
    }



    /**
     * Set two views and render their resepctive data, views does not share
     * data.
     */
    public function testAddViewsCheckRenderData()
    {
        $template = function ($data) {
            $str1 = $data["str1"] ?? null;
            $str2 = $data["str2"] ?? null;
            return "$str1, $str2:";
        };
        $view1 = new View();
        $view1->set($template, ["str1" => "str1"], 0, "callback");
        $view2 = new View();
        $view2->set($template, ["str2" => "str2"], 0, "callback");
        
        ob_start();
        $view1->render();
        $res = ob_get_contents();
        ob_end_clean();
        $exp = "str1, :";
        $this->assertEquals($exp, $res);

        ob_start();
        $view2->render();
        $res = ob_get_contents();
        ob_end_clean();
        $exp = ", str2:";
        $this->assertEquals($exp, $res);
    }



    /**
     * Set a view as array with data and add extra data which is then merged.
     */
    public function testAddViewMergeData()
    {
        $template = function ($data) {
            $str1 = $data["str1"] ?? null;
            $str2 = $data["str2"] ?? null;
            return "$str1, $str2:";
        };
        $view = new View();
        $view->set(
            [
                "callback" => $template,
                "data" => ["str1" => "str1"]
            ],
            ["str2" => "str2"],
            0,
            "callback"
        );
        
        ob_start();
        $view->render();
        $res = ob_get_contents();
        ob_end_clean();
        $exp = "str1, str2:";
        $this->assertEquals($exp, $res);
    }



    /**
     * Set a view as array with data and add extra data which is then merged,
     * check that numeric array can be used with $data and be merged.
     */
    public function testAddViewMergeDataWithArray()
    {
        $template = function ($data) {
            $str1 = $data["str1"] ?? null;
            $str2 = $data["str2"] ?? null;
            $str3a = $data["str3"]["a"] ?? null;
            $str3b = $data["str3"]["b"] ?? null;
            return "$str1, $str2, $str3a, $str3b:";
        };
        $view = new View();
        $view->set(
            [
                "callback" => $template,
                "data" => [
                    "str1" => "str1",
                    "str3" => ["a" => "a"]
                ]
            ],
            [
                "str2" => "str2",
                "str3" => ["b" => "b"]
            ],
            0,
            "callback"
        );
        
        ob_start();
        $view->render();
        $res = ob_get_contents();
        ob_end_clean();
        $exp = "str1, str2, a, b:";
        $this->assertEquals($exp, $res);
    }
}
