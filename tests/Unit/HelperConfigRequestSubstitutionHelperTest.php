<?php

namespace App\Helpers;

use Tests\TestCase;
use Illuminate\Http\Request;

class HelperConfigRequestSubstitutionHelper extends TestCase
{
	private $helper;
	
	protected function setUp()
	{
		$request = new MockRequest();
		
		$this->helper = new ConfigRequestSubstitutionHelper($request);
	}
	
	public function testConfigRequestSubstitutionHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof ConfigRequestSubstitutionHelper);
	}
	
	public function testSubstituteText()
	{
		$original_text = "some text {{with}} {{placeholders}}";
		$expected_text = "some text ~~with~~ ~~placeholders~~";
		
		$substituted_text = $this->helper->substitute_content_with_request_params($original_text);
		
		$this->assertEquals($substituted_text, $expected_text);
	}
	
	public function testSubstituteTextWithMissingParams()
	{
		$request = new MockRequestMissing();
		
		$helper = new ConfigRequestSubstitutionHelper($request);
		
		$original_text = "some text {{with}} {{placeholders}}";
		$expected_text = "some text  ";
		
		$substituted_text = $helper->substitute_content_with_request_params($original_text);
		
		$this->assertEquals($substituted_text, $expected_text);
	}
}

class MockRequest extends Request
{
	public function get($param_key, $default = null)
	{
		return "~~$param_key~~";
	}
}

class MockRequestMissing extends Request
{
	public function get($param_key, $default = null)
	{
		return "";
	}
}