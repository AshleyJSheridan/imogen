<?php

namespace App\Helpers;

use Tests\TestCase;
use Illuminate\Http\Request;

class HelperConfigHelperTest extends TestCase
{
	private $helper;
	private $request;
	private $requestSubstitutionHelper;
	private $campaign_name = '~~name~~';
	
	protected function setUp()
	{
		$this->request = new MockRequestWithRoute();
		$this->requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setConstructorArgs([$this->request])
			->getMock();
		$this->helper = new ConfigHelper($this->request, $this->requestSubstitutionHelper);
	}
	
	public function testConfigHelperConstruction()
	{
		$this->assertTrue($this->helper instanceof ConfigHelper);
	}
	
	public function testGetCampaignName()
	{
		$campaign_name = $this->helper->get_campaign_name();
		
		$this->assertEquals($campaign_name, '~~name~~');
	}
	
	public function testGetConfigValue()
	{
		$option_name = 'some option';
		$option_value = $this->helper->get($option_name);
		$expected_value = "campaigns.$this->campaign_name.$option_name";
		
		$this->assertEquals($option_value, $expected_value);
	}
	
	public function testGetConfigWithNullValueAndDefault()
	{
		$option_name = 'null option';
		$expected_value = "some value";
		$option_value = $this->helper->get($option_name, $expected_value);
		
		$this->assertEquals($option_value, $expected_value);
	}
	
	public function testGetConfigWithNullValue()
	{
		$option_name = 'null option';
		$option_value = $this->helper->get($option_name);
		
		$this->assertNull($option_value);
	}
	
	public function testGetForOverlay()
	{
		$expected_overlay_value = 'some overlay value';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setMethods(['substitute_content_with_request_params'])
			->setConstructorArgs([$this->request])
			->getMock();
		$requestSubstitutionHelper->method('substitute_content_with_request_params')
			->willReturn($expected_overlay_value);
		
		$helper = new ConfigHelper($request, $requestSubstitutionHelper);
		
		$overlay_value = $helper->get_for_overlay('some overlay name', 'some option');
		
		$this->assertEquals($overlay_value, $expected_overlay_value);
	}
	
	public function testGetForOverlayNull()
	{
		$expected_overlay_value = 'some overlay value from null';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setMethods(['substitute_content_with_request_params'])
			->setConstructorArgs([$this->request])
			->getMock();
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get_fallback_or_default_value'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get_fallback_or_default_value')
			->willReturn($expected_overlay_value);
		
		$overlay_value = $helper->get_for_overlay('some overlay name', 'null option');
		
		$this->assertEquals($overlay_value, $expected_overlay_value);
	}
	
	public function testGetForOverlayNullThrowsExceptionOnError()
	{
		$expected_overlay_value = 'some overlay value from null';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setMethods(['substitute_content_with_request_params'])
			->setConstructorArgs([$this->request])
			->getMock();
		$requestSubstitutionHelper->method('substitute_content_with_request_params')
			->willReturn(null);
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get_fallback_or_default_value'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get_fallback_or_default_value')
			->will($this->throwException(new \App\Exceptions\MissingConfigOptionException('')));
		
		$this->expectException(\App\Exceptions\MissingConfigOptionException::class);
		
		$overlay_value = $helper->get_for_overlay('some overlay name', 'null option');
	}
	
	public function testGetNonNullConfigValue()
	{
		$expected_overlay_value = 'some non null value';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setConstructorArgs([$this->request])
			->getMock();
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get')
			->willReturn($expected_overlay_value);
		
		$overlay_value = $helper->get_fallback_or_default_value('some value key');
		
		$this->assertEquals($overlay_value, $expected_overlay_value);
	}
	
	public function testGetNullConfigValueWithDefaultFallback()
	{
		$expected_overlay_value = 'some non null value';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setConstructorArgs([$this->request])
			->getMock();
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get')
			->willReturn(null);
		
		$overlay_value = $helper->get_fallback_or_default_value('some value key that is null', $expected_overlay_value, false);
		
		$this->assertEquals($overlay_value, $expected_overlay_value);
	}
	
	public function testGetNullConfigValueThrowsException()
	{
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setConstructorArgs([$this->request])
			->getMock();
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get')
			->willReturn(null);
		
		$this->expectException(\App\Exceptions\MissingConfigOptionException::class);
		
		$overlay_value = $helper->get_fallback_or_default_value('some value key that is null', 'some default');
	}
	
	public function testGetRandomOption()
	{
		$content_array = ['thing 0', 'thing 1', 'thing 2', 'thing 3', 'thing 4'];
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setConstructorArgs([$this->request])
			->getMock();
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get_for_overlay'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		$helper->method('get_for_overlay')
			->willReturn($content_array);
		
		$random_option = $helper->get_random('some overlay', 'some option');
		
		$this->assertEquals($random_option, $content_array[4]);
	}
	
	public function testSubstituteContentWithRequestParams()
	{
		$expected_value = 'some value';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setMethods(['substitute_content_with_request_params'])
			->setConstructorArgs([$this->request])
			->getMock();
		$requestSubstitutionHelper->method('substitute_content_with_request_params')
			->willReturn($expected_value);
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get_for_overlay'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		
		$substituted_value = $helper->substitute_content_with_request_params('some content');
		
		$this->assertEquals($substituted_value, $expected_value);
	}
	
	public function testGetForOverlayWithSubstitution()
	{
		$expected_value = 'some value';
		$request = new MockRequestWithRoute();
		$requestSubstitutionHelper = $this->getMockBuilder(ConfigRequestSubstitutionHelper::class)
			->setMethods(['substitute_content_with_request_params'])
			->setConstructorArgs([$this->request])
			->getMock();
		$requestSubstitutionHelper->method('substitute_content_with_request_params')
			->willReturn($expected_value);
		
		$helper = $this->getMockBuilder(ConfigHelper::class)
			->setMethods(['get_for_overlay'])
			->setConstructorArgs([$request, $requestSubstitutionHelper])
			->getMock();
		
		$overlay_value = $helper->get_for_overlay_with_request_param_substitution('some overlay', 'some option');
		
		$this->assertEquals($overlay_value, $expected_value);
	}
}

class MockRequestWithRoute extends Request
{
	function route($name=null)
	{
		return "~~$name~~";
	}
}

function config($option_name)
{
	if(strpos($option_name, 'null option'))
		return null;
	else
		return $option_name;
}

function rand($lower_limit, $upper_limit)
{
	return 4; // chosen by fair dice roll
			  // guaranteed to be random
}