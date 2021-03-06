<?php

use Unisharp\Laravelfilemanager\Tests;

class TestApi extends TestCase
{
    public function testFolder()
    {
        auth()->loginUsingId(1);

        $create = $this->getResponseByRouteName('getAddfolder', [
            'name' => 'testcase'
        ]);

        $create_duplicate = $this->getResponseByRouteName('getAddfolder', [
            'name' => 'testcase'
        ]);

        $create_empty = $this->getResponseByRouteName('getAddfolder', [
            'name' => ''
        ]);

        Config::set('lfm.alphanumeric_directory', true);
        $create_alphanumeric = $this->getResponseByRouteName('getAddfolder', [
            'name' => '測試資料夾'
        ]);

        $rename = $this->getResponseByRouteName('getRename', [
            'file' => 'testcase',
            'new_name' => 'testcase2'
        ]);

        $delete = $this->getResponseByRouteName('getDelete', [
            'item' => 'testcase2'
        ]);

        $this->assertEquals($create, 'OK');
        $this->assertEquals($create_duplicate, trans('laravel-filemanager::lfm.error-folder-exist'));
        $this->assertEquals($create_empty, trans('laravel-filemanager::lfm.error-folder-name'));
        $this->assertEquals($create_alphanumeric, trans('laravel-filemanager::lfm.error-folder-alnum'));
        $this->assertEquals($rename, 'OK');
        $this->assertEquals($delete, 'OK');
    }

    public function testUpload()
    {
        auth()->loginUsingId(1);

        $upload = $this->getResponseByRouteName('getDelete', [
            'upload' => base_path('vendor/unisharp/laravel-filemanager/public/images/test-folder/sleeping-dog.jpg')
        ]);

        $this->assertEquals($upload, 'OK');
    }

    private function getResponseByRouteName($route_name, $input = [])
    {
        $response = $this->call('GET', route('unisharp.lfm.' . $route_name), $input);
        $data = json_encode($response);
        return $response->getContent();
    }
}
