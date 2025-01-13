<?php

namespace RemoteSettingsApiTests;

use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase {

    public function testBooleanSettingsAreSerializedProperly() {
    	$this->assertTrue(\Setting::from_bool('foo', true)->get_value());
    	$this->assertTrue(\Setting::from_bool('foo', 'true')->get_value());

    	$this->assertFalse(\Setting::from_bool('foo', false)->get_value());
    	$this->assertFalse(\Setting::from_bool('foo', 'false')->get_value());
    }

    public function testIntegerSettingsAreSerializedProperly() {
		$this->assertSame(\Setting::from_int('foo', 0)->get_value(), 0);
		$this->assertSame(\Setting::from_int('foo', 1)->get_value(), 1);
        $this->assertSame(\Setting::from_int('foo', 42)->get_value(), 42);
        $this->assertSame(\Setting::from_int('foo', PHP_INT_MAX)->get_value(), PHP_INT_MAX);

        $this->assertSame(\Setting::from_int('foo', '0')->get_value(), 0);
		$this->assertSame(\Setting::from_int('foo', '1')->get_value(), 1);
        $this->assertSame(\Setting::from_int('foo', '42')->get_value(), 42);

        $this->assertSame(\Setting::from_int('foo', ' 0 ')->get_value(), 0);
		$this->assertSame(\Setting::from_int('foo', ' 1 ')->get_value(), 1);
        $this->assertSame(\Setting::from_int('foo', ' 42 ')->get_value(), 42);
    }

    public function testEnumSettingsAreSerializedProperly() {
    	$this->assertSame(\Setting::from_enum('foo', 0, [0, 1, 2])->get_value(), 0);
    	$this->assertNull(\Setting::from_enum('foo', true, [0, 1, 2])->get_value(), 0);
    	$this->assertSame(\Setting::from_enum('foo', true, [true, false, null])->get_value(), true);
    	$this->assertSame(\Setting::from_enum('foo', false, [true, false, null])->get_value(), false);
    }

    public function testColorSettingsAreSerializedProperly() {
        $this->assertSame(\Setting::from_color('foo', '#fff')->get_value(), '#ffffff');
        $this->assertSame(\Setting::from_color('foo', '#ffffff')->get_value(), '#ffffff');
        $this->assertSame(\Setting::from_color('foo', 'fff')->get_value(), '#ffffff');
        $this->assertSame(\Setting::from_color('foo', 'ffffff')->get_value(), '#ffffff');
        $this->assertSame(\Setting::from_color('foo', 'ffffffff')->get_value(), '#ffffff');
        $this->assertSame(\Setting::from_color('foo', 'ffffff01')->get_value(), '#ffffff01');
        $this->assertSame(\Setting::from_color('foo', 'ffffff11')->get_value(), '#ffffff11');

        $this->assertNull(\Setting::from_color('foo', 'ff')->get_value());
        $this->assertNull(\Setting::from_color('foo', 'ff57338080')->get_value());
        $this->assertNull(\Setting::from_color('foo', 'zzzzzz')->get_value());
        $this->assertNull(\Setting::from_color('foo', '')->get_value());
        $this->assertNull(\Setting::from_color('foo', 'yellow')->get_value());
        $this->assertNull(\Setting::from_color('foo', 'blue')->get_value());
        $this->assertNull(\Setting::from_color('foo', 'red')->get_value());
    }

    public function testDateSettingsAreSerializedProperly() {
        $this->assertSame(\Setting::from_date('foo', 'now')->get_value(), date('c'));
        $this->assertSame(\Setting::from_date('foo', 'Jan 1, 2000')->get_value(), '2000-01-01T00:00:00+00:00');
        $this->assertNull(\Setting::from_date('foo', 'never')->get_value());
    }
}
